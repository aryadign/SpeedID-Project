<?php

namespace App\Services;

use App\Models\QueueTicket;
use App\Models\Service;
use App\Models\ServiceSlot;
use App\Models\User;
use App\Notifications\QueueStatusNotification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class QueueService
{
    public function getDefaultSlotsForService(Service $service, string $date): array
    {
        // Pool of possible time slots
        $pool = [
            ['start_time' => '08:00', 'end_time' => '10:00'],
            ['start_time' => '09:00', 'end_time' => '11:00'],
            ['start_time' => '10:00', 'end_time' => '12:00'],
            ['start_time' => '11:00', 'end_time' => '13:00'],
            ['start_time' => '12:00', 'end_time' => '14:00'],
            ['start_time' => '13:00', 'end_time' => '15:00'],
            ['start_time' => '14:00', 'end_time' => '16:00'],
            ['start_time' => '15:00', 'end_time' => '17:00'],
        ];

        // Seed with a hash of service_id and date for deterministic randomness
        $seed = crc32($service->id . '_' . $date);
        mt_srand($seed);

        // Randomly decide how many slots to select (e.g., between 2 and 5)
        $numSlots = mt_rand(2, 5);

        // Shuffle index array deterministically
        $indices = range(0, count($pool) - 1);
        
        // Simple deterministic shuffle using mt_rand
        for ($i = count($indices) - 1; $i > 0; $i--) {
            $j = mt_rand(0, $i);
            $temp = $indices[$i];
            $indices[$i] = $indices[$j];
            $indices[$j] = $temp;
        }

        // Take the first $numSlots indices and sort them to keep chronological order
        $selectedIndices = array_slice($indices, 0, $numSlots);
        sort($selectedIndices);

        $slots = [];
        foreach ($selectedIndices as $index) {
            $slots[] = [
                'date' => $date,
                'start_time' => $pool[$index]['start_time'],
                'end_time' => $pool[$index]['end_time'],
                'quota' => mt_rand(5, 15), // Random quota between 5 and 15
            ];
        }

        // Reset the mt_rand seed back to a random state
        mt_srand();

        return $slots;
    }

    public function getAvailableSlots(Service $service, string $date)
    {
        $exists = $service->serviceSlots()
            ->whereDate('date', $date)
            ->exists();

        if (!$exists) {
            $slotsData = $this->getDefaultSlotsForService($service, $date);
            $service->serviceSlots()->createMany($slotsData);
        }

        return $service->serviceSlots()
            ->whereDate('date', $date)
            ->where('quota', '>', 0)
            ->get()
            ->map(function ($slot) {
                $bookedCount = $slot->queueTickets()->where('status', '!=', 'batal')->count();
                $slot->setAttribute('available', max(0, $slot->quota - $bookedCount));
                return $slot;
            })
            ->filter(fn ($slot) => $slot->available > 0)
            ->values();
    }

    public function createBooking(User $user, ServiceSlot $slot): QueueTicket
    {
        $today = now()->toDateString();
        $queueNumber = $slot->queueTickets()->whereDate('created_at', $today)->max('queue_number');
        $nextNumber = $queueNumber ? (int) substr($queueNumber, -3) + 1 : 1;
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $ticket = QueueTicket::create([
            'user_id' => $user->id,
            'service_slot_id' => $slot->id,
            'queue_number' => $formattedNumber,
            'booking_code' => strtoupper(Str::random(8)),
            'estimated_wait' => $this->calculateEstimatedWait($slot, $nextNumber),
            'status' => 'menunggu',
        ]);

        $qrData = json_encode([
            'booking_code' => $ticket->booking_code,
            'queue_number' => $ticket->queue_number,
            'user_id' => $user->id,
        ]);
        $ticket->update(['qr_code' => base64_encode(QrCode::format('svg')->size(200)->generate($qrData))]);

        activity()->performedOn($ticket)->causedBy($user)->withProperties(['queue_number' => $ticket->queue_number, 'service' => $slot->service->name])->log('membuat antrean');
        $user->notify(new QueueStatusNotification($ticket, 'Antrean berhasil dibuat. Nomor: ' . $ticket->queue_number));

        return $ticket;
    }

    public function callNext(QueueTicket $ticket): void
    {
        $ticket->update(['status' => 'dipanggil', 'called_at' => now()]);
        activity()->performedOn($ticket)->causedBy(auth()->user())->log('memanggil antrean');
    }

    public function skip(QueueTicket $ticket): void
    {
        $ticket->update(['status' => 'batal', 'cancelled_at' => now()]);
        activity()->performedOn($ticket)->causedBy(auth()->user())->log('melewati antrean');
    }

    public function complete(QueueTicket $ticket): void
    {
        $ticket->update(['status' => 'selesai', 'completed_at' => now()]);
        activity()->performedOn($ticket)->causedBy(auth()->user())->log('menyelesaikan antrean');
    }

    public function cancel(QueueTicket $ticket): void
    {
        $ticket->update(['status' => 'batal', 'cancelled_at' => now()]);
        activity()->performedOn($ticket)->causedBy(auth()->user())->log('membatalkan antrean');
    }

    public function currentQueue(ServiceSlot $slot)
    {
        return $slot->queueTickets()
            ->whereDate('created_at', now()->toDateString())
            ->orderBy('created_at')
            ->get();
    }

    private function calculateEstimatedWait(ServiceSlot $slot, int $position): int
    {
        $avgDuration = $slot->service->duration ?? 15;
        return ($position - 1) * $avgDuration;
    }
}
