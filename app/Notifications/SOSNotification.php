<?php

namespace App\Notifications;

use App\Models\SOSRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SOSNotification extends Notification
{
    use Queueable;

    public function __construct(public SOSRequest $sos, public string $message) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'SOS ' . ucfirst($this->sos->emergency_type),
            'message' => $this->message,
            'url' => route('sos.show', $this->sos->id),
        ];
    }
}
