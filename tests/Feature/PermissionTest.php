<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()->create()->assignRole('User');
        $this->admin = User::factory()->create()->assignRole('Admin');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/dashboard')
            ->assertOk();
    }

    public function test_user_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->user)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_guest_cannot_access_admin(): void
    {
        $this->get('/admin/dashboard')
            ->assertRedirect('/login');
    }

    public function test_admin_can_manage_institutions(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/institutions')
            ->assertOk();
    }

    public function test_admin_can_manage_news_categories(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/news/categories', [
                'name' => 'Kategori Baru',
            ])
            ->assertRedirect();
    }

    public function test_user_has_dashboard_access(): void
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertOk();
    }

    public function test_admin_has_dashboard_access(): void
    {
        $this->actingAs($this->admin)
            ->get('/dashboard')
            ->assertOk();
    }
}
