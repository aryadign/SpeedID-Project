<?php

namespace App\Services;

use App\Models\Institution;
use App\Models\NewsPost;
use App\Models\QueueTicket;
use App\Models\Report;
use App\Models\SOSRequest;
use App\Models\User;

class DashboardService
{
    public function getUserDashboard(User $user): array
    {
        return [
            'active_queue' => $user->queueTickets()
                ->whereIn('status', ['menunggu', 'dipanggil'])
                ->with('serviceSlot.service.institution')
                ->latest()
                ->first(),
            'recent_reports' => $user->reports()
                ->with('category')
                ->latest()
                ->take(5)
                ->get(),
            'sos_history' => $user->sosRequests()
                ->latest()
                ->take(5)
                ->get(),
            'latest_news' => NewsPost::where('status', 'published')
                ->whereNotNull('published_at')
                ->latest('published_at')
                ->take(5)
                ->get(),
        ];
    }

    public function getAdminDashboard(): array
    {
        return [
            'total_users' => User::count(),
            'total_institutions' => Institution::count(),
            'total_queues' => QueueTicket::count(),
            'active_queues' => QueueTicket::whereIn('status', ['menunggu', 'dipanggil'])->count(),
            'total_reports' => Report::count(),
            'pending_reports' => Report::whereIn('status', ['terkirim', 'diverifikasi'])->count(),
            'total_sos' => SOSRequest::count(),
            'active_sos' => SOSRequest::whereIn('status', ['masuk', 'diproses'])->count(),
            'total_articles' => NewsPost::count(),
            'recent_reports' => Report::with(['user', 'category'])
                ->latest()
                ->take(10)
                ->get(),
            'recent_sos' => SOSRequest::with('user')
                ->latest()
                ->take(10)
                ->get(),
        ];
    }
}
