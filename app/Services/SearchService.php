<?php

namespace App\Services;

use App\Models\Institution;
use App\Models\NewsPost;
use App\Models\Report;
use Illuminate\Support\Collection;

class SearchService
{
    public function search(string $query, $user = null, int $limit = 5): Collection
    {
        $results = collect();

        $institutions = Institution::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%");
            })
            ->take($limit)->get();

        $services = \App\Models\Service::where('is_active', true)
            ->whereHas('institution', fn($q) => $q->where('is_active', true))
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('institution')
            ->take($limit)->get();

        $news = NewsPost::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->take($limit)->get();

        $isAdmin = $user && $user->hasRole('Admin');

        $results = $results->merge($institutions->map(fn($i) => [
            'type' => 'Instansi',
            'title' => $i->name,
            'url' => $isAdmin
                ? route('admin.institutions.edit', $i->id)
                : route('queue.booking', ['institution_id' => $i->id]),
            'icon' => 'building-2',
        ]));

        $results = $results->merge($services->map(fn($s) => [
            'type' => 'Layanan',
            'title' => $s->name . ' — ' . $s->institution->name,
            'url' => $isAdmin
                ? route('admin.institutions.edit', $s->institution_id)
                : route('queue.booking', ['institution_id' => $s->institution_id, 'service_id' => $s->id]),
            'icon' => 'concierge-bell',
        ]));

        $results = $results->merge($news->map(fn($n) => [
            'type' => 'Berita',
            'title' => $n->title,
            'url' => route('news.show', $n),
            'icon' => 'newspaper',
        ]));

        if ($user) {
            $reports = Report::where('title', 'like', "%{$query}%")
                ->where(function ($q) use ($user) {
                    if (!$user->hasRole('Admin')) $q->where('user_id', $user->id);
                })
                ->take($limit)->get();

            $results = $results->merge($reports->map(fn($r) => [
                'type' => 'Laporan',
                'title' => $r->title,
                'url' => route('reports.show', $r->id),
                'icon' => 'file-text',
            ]));
        }

        return $results;
    }
}
