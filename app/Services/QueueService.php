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
    public function getAvailableSlots(Service $service, string $date)
    {
        return $service->serviceSlots()
            ->whereDate('date', $date)
            ->where('quota', '>', 0)
            ->get()
            ->filter(fn ($slot) => $slot->queueTickets()->whereDate('created_at', $date)->count() < $slot->quota)
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
