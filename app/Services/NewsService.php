<?php

namespace App\Services;

use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Support\Str;

class NewsService
{
    public function createPost(User $user, array $data): NewsPost
    {
        $data['user_id'] = $user->id;
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        $post = NewsPost::create($data);
        activity()->performedOn($post)->causedBy($user)->log('membuat berita');
        return $post;
    }

    public function updatePost(NewsPost $post, array $data): void
    {
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $post->update($data);
    }

    public function publish(NewsPost $post): void
    {
        $post->update(['status' => 'published', 'published_at' => now()]);
        activity()->performedOn($post)->causedBy(auth()->user())->log('menerbitkan berita');
    }

    public function unpublish(NewsPost $post): void
    {
        $post->update(['status' => 'draft', 'published_at' => null]);
        activity()->performedOn($post)->causedBy(auth()->user())->log('menarik berita');
    }

    public function getPublished(array $filters = [])
    {
        $query = NewsPost::with(['category', 'user'])
            ->where('status', 'published');

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['subdistrict_id'])) {
            $query->where('subdistrict_id', $filters['subdistrict_id']);
        }
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('content', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->latest('published_at')->paginate(12);
    }

    public function getEmergencyAlerts()
    {
        return NewsPost::where('is_emergency', true)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();
    }
}
