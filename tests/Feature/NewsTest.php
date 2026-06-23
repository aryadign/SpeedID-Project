<?php

namespace Tests\Feature;

use App\Models\NewsCategory;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected NewsCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::factory()->create()->assignRole('Admin');
        $this->category = NewsCategory::where('slug', 'berita')->first();
    }

    public function test_public_can_view_news_list(): void
    {
        $this->get('/news')
            ->assertOk();
    }

    public function test_public_can_view_published_news(): void
    {
        $post = NewsPost::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Test News',
            'slug' => 'test-news',
            'content' => 'Content',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('news.show', $post))
            ->assertOk();
    }

    public function test_unpublished_news_returns_404(): void
    {
        $post = NewsPost::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Draft News',
            'slug' => 'draft-news',
            'content' => 'Draft content',
            'status' => 'draft',
        ]);

        $this->get(route('news.show', $post))
            ->assertNotFound();
    }

    public function test_admin_can_view_news_management(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/news')
            ->assertOk();
    }

    public function test_user_cannot_access_admin_news(): void
    {
        $user = User::factory()->create()->assignRole('User');

        $this->actingAs($user)
            ->get('/admin/news')
            ->assertForbidden();
    }

    public function test_admin_can_create_news(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/news', [
                'category_id' => $this->category->id,
                'title' => 'New Article',
                'content' => 'Article content',
            ])
            ->assertRedirect();
    }

    public function test_admin_can_edit_news(): void
    {
        $post = NewsPost::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Original Title',
            'slug' => 'original-title',
            'content' => 'Original content',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->put("/admin/news/{$post->id}", [
                'title' => 'Updated Title',
                'content' => 'Updated content',
            ])
            ->assertRedirect();
    }

    public function test_admin_can_delete_news(): void
    {
        $post = NewsPost::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => ' deletable',
            'slug' => 'deletable-news',
            'content' => 'To be deleted',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin)
            ->delete("/admin/news/{$post->id}")
            ->assertRedirect();
    }

    public function test_emergency_alerts_returns_json(): void
    {
        $this->get('/news/emergency/alerts')
            ->assertJson([]);
    }
}
