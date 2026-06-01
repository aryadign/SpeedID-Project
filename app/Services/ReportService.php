<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportComment;
use App\Models\ReportMedia;
use App\Models\User;
use App\Notifications\ReportStatusNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function createReport(User $user, array $data, array $files = []): Report
    {
        $report = Report::create([
            'user_id' => $user->id,
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'anonymous' => $data['anonymous'] ?? false,
            'status' => 'terkirim',
        ]);

        foreach ($files as $file) {
            $this->uploadMedia($report, $file);
        }

        activity()->performedOn($report)->causedBy($user)->withProperties(['tracking_code' => $report->tracking_code])->log('membuat laporan');

        return $report;
    }

    public function uploadMedia(Report $report, UploadedFile $file): ReportMedia
    {
        $path = $file->store('reports/' . $report->id, 'public');

        return $report->media()->create([
            'file_path' => $path,
            'file_type' => str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image',
            'mime_type' => $file->getMimeType(),
        ]);
    }

    public function updateStatus(Report $report, string $status, ?string $reason = null): void
    {
        $oldStatus = $report->status;

        $timestamp = match ($status) {
            'diverifikasi' => 'verified_at',
            'diproses' => 'processed_at',
            'selesai' => 'completed_at',
            default => null,
        };

        $data = ['status' => $status];
        if ($timestamp) $data[$timestamp] = now();
        if ($status === 'ditolak') $data['rejection_reason'] = $reason;

        $report->update($data);

        activity()->performedOn($report)->causedBy(auth()->user())->withProperties(['old_status' => $oldStatus, 'new_status' => $status])->log('memperbarui status laporan');
        $report->user->notify(new ReportStatusNotification($report, 'Status laporan berubah menjadi ' . $status));
    }

    public function addComment(Report $report, User $user, string $comment, ?UploadedFile $file = null): ReportComment
    {
        $data = [
            'report_id' => $report->id,
            'user_id' => $user->id,
            'comment' => $comment,
        ];

        if ($file) {
            $data['media_path'] = $file->store('reports/' . $report->id . '/comments', 'public');
        }

        $comment = $report->comments()->create($data);
        activity()->performedOn($report)->causedBy($user)->log('menambahkan komentar');
        return $comment;
    }

    public function getTimeline(Report $report): array
    {
        $timeline = [];
        $statuses = [
            'terkirim' => $report->created_at,
            'diverifikasi' => $report->verified_at,
            'diproses' => $report->processed_at,
            'selesai' => $report->completed_at,
        ];

        foreach ($statuses as $status => $time) {
            if ($time) $timeline[] = ['status' => $status, 'time' => $time];
        }

        if ($report->status === 'ditolak') {
            $timeline[] = ['status' => 'ditolak', 'time' => $report->updated_at, 'reason' => $report->rejection_reason];
        }

        return $timeline;
    }
}
