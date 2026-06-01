<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\NewsCategory;
use App\Models\NewsPost;
use App\Models\Subdistrict;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsPostController extends Controller
{
    public function __construct(
        protected NewsService $newsService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['category_id', 'search', 'subdistrict_id']);
        $posts = $this->newsService->getPublished($filters);
        $categories = NewsCategory::where('is_active', true)->get();
        return view('speednews.index', compact('posts', 'categories'));
    }

    public function show(NewsPost $post)
    {
        if ($post->status !== 'published') {
            abort(404);
        }
        $post->load(['category', 'user']);
        $related = NewsPost::with('category')
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(4)
            ->get();
        return view('speednews.show', compact('post', 'related'));
    }

    public function adminIndex()
    {
        $posts = NewsPost::with(['category', 'user'])->latest()->paginate(10);
        return view('speednews.admin.index', compact('posts'));
    }

    public function create()
    {
        $categories = NewsCategory::where('is_active', true)->get();
        $subdistricts = Subdistrict::with('district.province')->get();
        return view('speednews.admin.create', compact('categories', 'subdistricts'));
    }

    public function store(StoreNewsRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }
        $post = $this->newsService->createPost($request->user(), $data);
        return redirect()->route('admin.news.edit', $post->id)
            ->with('success', 'Berita berhasil dibuat');
    }

    public function edit(NewsPost $post)
    {
        $categories = NewsCategory::where('is_active', true)->get();
        $subdistricts = Subdistrict::with('district.province')->get();
        return view('speednews.admin.edit', compact('post', 'categories', 'subdistricts'));
    }

    public function update(UpdateNewsRequest $request, NewsPost $post)
    {
        $data = $request->validated();
        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) Storage::disk('public')->delete($post->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }
        $this->newsService->updatePost($post, $data);
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy(NewsPost $post)
    {
        if ($post->thumbnail) Storage::disk('public')->delete($post->thumbnail);
        $post->delete();
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus');
    }

    public function emergencyAlerts()
    {
        $alerts = $this->newsService->getEmergencyAlerts();
        return response()->json($alerts);
    }
}
