<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\NewsPost;
use App\Models\Report;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return view('search.index', ['query' => $query, 'results' => collect()]);
        }

        $institutions = Institution::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%");
            })
            ->take(5)->get();

        $services = \App\Models\Service::where('is_active', true)
            ->whereHas('institution', function ($q) {
                $q->where('is_active', true);
            })
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('institution')
            ->take(5)->get();

        $news = NewsPost::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->take(5)->get();

        $user = $request->user();
        $isAdmin = $user && $user->hasRole('Admin');

        $results = collect();

        $results = $results->merge($institutions->map(function ($i) use ($isAdmin) {
            $url = $isAdmin 
                ? route('admin.institutions.edit', $i->id) 
                : route('queue.booking', ['institution_id' => $i->id]);
            return [
                'type' => 'Instansi',
                'title' => $i->name,
                'url' => $url,
                'icon' => 'building-2'
            ];
        }));

        $results = $results->merge($services->map(function ($s) use ($isAdmin) {
            $url = $isAdmin
                ? route('admin.institutions.edit', $s->institution_id)
                : route('queue.booking', ['institution_id' => $s->institution_id, 'service_id' => $s->id]);
            return [
                'type' => 'Layanan',
                'title' => $s->name . ' — ' . $s->institution->name,
                'url' => $url,
                'icon' => 'concierge-bell'
            ];
        }));

        $results = $results->merge($news->map(fn($n) => [
            'type' => 'Berita',
            'title' => $n->title,
            'url' => route('news.show', $n),
            'icon' => 'newspaper'
        ]));

        if ($user) {
            $reports = Report::where('title', 'like', "%{$query}%")
                ->where(function ($q) use ($user) {
                    if (!$user->hasRole('Admin')) $q->where('user_id', $user->id);
                })
                ->take(5)->get();
            $results = $results->merge($reports->map(fn($r) => [
                'type' => 'Laporan',
                'title' => $r->title,
                'url' => route('reports.show', $r->id),
                'icon' => 'file-text'
            ]));
        }

        return view('search.index', compact('query', 'results'));
    }
}
