<?php

namespace App\Notifications;

use App\Models\QueueTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class QueueStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public QueueTicket $ticket, public string $message) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Antrean ' . $this->ticket->queue_number,
            'message' => $this->message,
            'url' => route('queue.tickets.show', $this->ticket->id),
        ];
    }
}
