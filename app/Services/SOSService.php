<?php

namespace App\Services;

use App\Models\SOSRequest;
use App\Models\User;
use App\Notifications\SOSNotification;
use Illuminate\Support\Facades\Notification;

class SOSService
{
    public function createSOSRequest(User $user, array $data): SOSRequest
    {
        $sos = SOSRequest::create([
            'user_id' => $user->id,
            'emergency_type' => $data['emergency_type'],
            'note' => $data['note'] ?? null,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'status' => 'masuk',
        ]);

        activity()->performedOn($sos)->causedBy($user)->withProperties(['emergency_type' => $sos->emergency_type])->log('mengirim permintaan darurat');
        $admins = User::role('Admin')->get();
        Notification::send($admins, new SOSNotification($sos, 'Permintaan darurat baru: ' . $sos->emergency_type));

        return $sos;
    }

    public function updateStatus(SOSRequest $sos, string $status, ?User $user = null): void
    {
        $timestamp = match ($status) {
            'diproses' => 'processed_at',
            'dalam_penanganan' => 'handling_at',
            'selesai' => 'completed_at',
            default => null,
        };

        $data = ['status' => $status];
        if ($timestamp) $data[$timestamp] = now();

        $sos->update($data);
        activity()->performedOn($sos)->causedBy(auth()->user())->withProperties(['new_status' => $status])->log('memperbarui status SOS');
    }

    public function getActiveSOS()
    {
        return SOSRequest::whereIn('status', ['masuk', 'diproses', 'dalam_penanganan'])
            ->with('user')
            ->latest()
            ->get();
    }
}
