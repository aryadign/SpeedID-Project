<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_guest_cannot_access_reports()
    {
        $this->get('/reports')->assertRedirect('/login');
        $this->get('/reports/create')->assertRedirect('/login');
    }

    public function test_user_can_view_reports_list()
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $this->actingAs($user)
            ->get('/reports')
            ->assertOk();
    }

    public function test_user_can_create_report()
    {
        $user = User::factory()->create();
        $user->assignRole('User');
        $category = ReportCategory::create(['name' => 'Jalan Rusak']);

        $this->actingAs($user)
            ->post('/reports', [
                'category_id' => $category->id,
                'title' => 'Test Laporan',
                'description' => 'Deskripsi laporan',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reports', [
            'user_id' => $user->id,
            'title' => 'Test Laporan',
        ]);
    }

    public function test_report_requires_valid_category()
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $this->actingAs($user)
            ->post('/reports', [
                'category_id' => 999,
                'title' => 'Test',
                'description' => 'Test',
            ])
            ->assertSessionHasErrors('category_id');
    }

    public function test_report_requires_title()
    {
        $user = User::factory()->create();
        $user->assignRole('User');
        $category = ReportCategory::create(['name' => 'Jalan Rusak']);

        $this->actingAs($user)
            ->post('/reports', [
                'category_id' => $category->id,
                'title' => '',
                'description' => 'Test deskripsi',
            ])
            ->assertSessionHasErrors('title');
    }

    public function test_user_can_view_their_report()
    {
        $user = User::factory()->create();
        $user->assignRole('User');
        $category = ReportCategory::create(['name' => 'Jalan Rusak']);

        $report = Report::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test Report',
            'description' => 'Test description',
            'status' => 'terkirim',
        ]);

        $this->actingAs($user)
            ->get('/reports/' . $report->id)
            ->assertOk()
            ->assertSee('Test Report');
    }

    public function test_user_cannot_view_others_report()
    {
        $userA = User::factory()->create();
        $userA->assignRole('User');
        $userB = User::factory()->create();
        $userB->assignRole('User');
        $category = ReportCategory::create(['name' => 'Jalan Rusak']);

        $report = Report::create([
            'user_id' => $userA->id,
            'category_id' => $category->id,
            'title' => 'Test Report',
            'description' => 'Test description',
            'status' => 'terkirim',
        ]);

        $this->actingAs($userB)
            ->get('/reports/' . $report->id)
            ->assertForbidden();
    }

    public function test_report_tracking_by_code()
    {
        $user = User::factory()->create();
        $user->assignRole('User');
        $category = ReportCategory::create(['name' => 'Jalan Rusak']);
        $trackingCode = substr((string) Str::uuid(), 0, 8);

        $report = Report::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test Report',
            'description' => 'Test description',
            'status' => 'terkirim',
        ]);

        $report->tracking_code = $trackingCode;
        $report->save();

        $this->post('/track', ['code' => $trackingCode])
            ->assertOk()
            ->assertSee('Test Report');
    }

    public function test_user_can_add_comment()
    {
        $user = User::factory()->create();
        $user->assignRole('User');
        $category = ReportCategory::create(['name' => 'Jalan Rusak']);

        $report = Report::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test Report',
            'description' => 'Test description',
            'status' => 'terkirim',
        ]);

        $this->actingAs($user)
            ->post('/reports/' . $report->id . '/comments', [
                'comment' => 'Test komentar',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('report_comments', [
            'report_id' => $report->id,
            'user_id' => $user->id,
            'comment' => 'Test komentar',
        ]);
    }

    public function test_admin_can_update_report_status()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $user = User::factory()->create();
        $user->assignRole('User');
        $category = ReportCategory::create(['name' => 'Jalan Rusak']);

        $report = Report::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test Report',
            'description' => 'Test description',
            'status' => 'terkirim',
        ]);

        $this->actingAs($admin)
            ->post('/reports/' . $report->id . '/status', [
                'status' => 'diverifikasi',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'diverifikasi',
        ]);
    }
}
