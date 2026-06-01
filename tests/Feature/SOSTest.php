<?php

namespace Tests\Feature;

use App\Models\EmergencyContact;
use App\Models\SOSRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SOSTest extends TestCase
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

    public function test_guest_cannot_access_sos(): void
    {
        $this->get('/sos')->assertRedirect('/login');
    }

    public function test_user_can_view_sos_page(): void
    {
        $this->actingAs($this->user)
            ->get('/sos')
            ->assertOk();
    }

    public function test_user_can_create_sos(): void
    {
        $this->actingAs($this->user)
            ->post('/sos', [
                'emergency_type' => 'ambulans',
                'latitude' => -6.2,
                'longitude' => 106.8,
            ])
            ->assertRedirect();
    }

    public function test_sos_requires_emergency_type(): void
    {
        $this->actingAs($this->user)
            ->post('/sos', [
                'latitude' => -6.2,
                'longitude' => 106.8,
            ])
            ->assertSessionHasErrors('emergency_type');
    }

    public function test_sos_requires_location(): void
    {
        $this->actingAs($this->user)
            ->post('/sos', [
                'emergency_type' => 'ambulans',
            ])
            ->assertSessionHasErrors(['latitude', 'longitude']);
    }

    public function test_user_can_view_their_sos(): void
    {
        $sos = SOSRequest::create([
            'user_id' => $this->user->id,
            'emergency_type' => 'ambulans',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'status' => 'masuk',
        ]);

        $this->actingAs($this->user)
            ->get("/sos/{$sos->id}")
            ->assertOk();
    }

    public function test_admin_can_update_sos_status(): void
    {
        $sos = SOSRequest::create([
            'user_id' => $this->user->id,
            'emergency_type' => 'ambulans',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'status' => 'masuk',
        ]);

        $this->actingAs($this->admin)
            ->post("/sos/{$sos->id}/status", [
                'status' => 'diproses',
            ])
            ->assertRedirect();
    }

    public function test_sos_active_returns_json(): void
    {
        $this->get('/sos/active')
            ->assertJson([]);
    }

    public function test_emergency_contacts_list(): void
    {
        EmergencyContact::create([
            'user_id' => $this->admin->id,
            'name' => 'Polsek',
            'phone_number' => '123',
            'relationship' => 'polisi',
        ]);

        $this->actingAs($this->admin)
            ->get('/admin/contacts')
            ->assertOk();
    }
}
