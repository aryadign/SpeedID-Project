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

        $institutions = Institution::where('name', 'like', "%{$query}%")->take(5)->get();
        $news = NewsPost::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->take(5)->get();

        $results = collect()
            ->merge($institutions->map(fn($i) => ['type' => 'Instansi', 'title' => $i->name, 'url' => route('admin.institutions.edit', $i->id), 'icon' => 'building-2']))
            ->merge($news->map(fn($n) => ['type' => 'Berita', 'title' => $n->title, 'url' => route('news.show', $n), 'icon' => 'newspaper']));

        if ($request->user()) {
            $reports = Report::where('title', 'like', "%{$query}%")
                ->where(function ($q) use ($request) {
                    if (!$request->user()->hasRole('Admin')) $q->where('user_id', $request->user()->id);
                })
                ->take(5)->get();
            $results = $results->merge($reports->map(fn($r) => ['type' => 'Laporan', 'title' => $r->title, 'url' => route('reports.show', $r->id), 'icon' => 'file-text']));
        }

        return view('search.index', compact('query', 'results'));
    }
}
