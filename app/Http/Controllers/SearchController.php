<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return view('search.index', ['query' => $query, 'results' => collect()]);
        }

        $results = app(SearchService::class)->search($query, $request->user(), 5);

        return view('search.index', compact('query', 'results'));
    }

    public function live(Request $request)
    {
        $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = app(SearchService::class)->search($query, $request->user(), 3);

        return response()->json($results->values());
    }
}
