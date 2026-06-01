<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public Report $report, public string $message) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Laporan: ' . $this->report->title,
            'message' => $this->message,
            'url' => route('reports.show', $this->report->id),
        ];
    }
}
