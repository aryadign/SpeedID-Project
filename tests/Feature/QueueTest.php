<?php

namespace Tests\Feature;

use App\Models\Institution;
use App\Models\QueueTicket;
use App\Models\Service;
use App\Models\ServiceSlot;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    private function createServiceSlot(int $quota = 10): ServiceSlot
    {
        $institution = Institution::create([
            'name' => 'Test Institution',
            'description' => 'Test Description',
            'address' => 'Test Address',
            'is_active' => true,
        ]);

        $service = Service::create([
            'institution_id' => $institution->id,
            'name' => 'Test Service',
            'description' => 'Test Service Description',
            'duration' => 15,
            'daily_quota' => 100,
            'is_active' => true,
        ]);

        return ServiceSlot::create([
            'service_id' => $service->id,
            'date' => now()->toDateString(),
            'start_time' => '08:00',
            'end_time' => '12:00',
            'quota' => $quota,
        ]);
    }

    public function test_guest_cannot_book_queue(): void
    {
        $response = $this->get(route('queue.booking'));

        $response->assertRedirectToRoute('login');
    }

    public function test_user_can_view_booking_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $response = $this->actingAs($user)->get(route('queue.booking'));

        $response->assertOk();
    }

    public function test_user_can_book_queue(): void
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $slot = $this->createServiceSlot();

        $this->mock(\App\Services\QueueService::class, function ($mock) use ($user, $slot) {
            $ticket = QueueTicket::create([
                'user_id' => $user->id,
                'service_slot_id' => $slot->id,
                'queue_number' => '001',
                'booking_code' => 'MOCKCODE',
                'estimated_wait' => 0,
                'status' => 'menunggu',
            ]);

            $mock->shouldReceive('createBooking')
                ->with(\Mockery::type(User::class), \Mockery::type(ServiceSlot::class))
                ->andReturn($ticket);
        });

        $response = $this->actingAs($user)->post(route('queue.book'), [
            'service_slot_id' => $slot->id,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('queue.tickets.show', QueueTicket::first()));
    }

    public function test_user_cannot_book_full_slot(): void
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $slot = $this->createServiceSlot(quota: 0);

        $response = $this->actingAs($user)->post(route('queue.book'), [
            'service_slot_id' => $slot->id,
        ]);

        $response->assertInvalid(['service_slot_id']);
        $this->assertDatabaseCount('queue_tickets', 0);
    }

    public function test_user_can_view_their_tickets(): void
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $slot = $this->createServiceSlot();
        $ticket = QueueTicket::create([
            'user_id' => $user->id,
            'service_slot_id' => $slot->id,
            'queue_number' => '001',
            'booking_code' => 'TESTCODE',
            'estimated_wait' => 0,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($user)->get(route('queue.tickets'));

        $response->assertOk();
        $response->assertSee($ticket->queue_number);
    }

    public function test_user_can_view_ticket_detail(): void
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $slot = $this->createServiceSlot();
        $ticket = QueueTicket::create([
            'user_id' => $user->id,
            'service_slot_id' => $slot->id,
            'queue_number' => '001',
            'booking_code' => 'TESTCODE',
            'estimated_wait' => 0,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($user)->get(route('queue.tickets.show', $ticket));

        $response->assertOk();
        $response->assertSee($ticket->queue_number);
    }

    public function test_user_cannot_view_others_ticket(): void
    {
        $userA = User::factory()->create();
        $userA->assignRole('User');

        $userB = User::factory()->create();
        $userB->assignRole('User');

        $slot = $this->createServiceSlot();
        $ticket = QueueTicket::create([
            'user_id' => $userA->id,
            'service_slot_id' => $slot->id,
            'queue_number' => '001',
            'booking_code' => 'TESTCODE',
            'estimated_wait' => 0,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($userB)->get(route('queue.tickets.show', $ticket));

        $response->assertForbidden();
    }

    public function test_admin_can_view_all_tickets(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->get(route('admin.queue.index'));

        $response->assertOk();
    }

    public function test_queue_display_returns_200(): void
    {
        $slot = $this->createServiceSlot();

        $user = User::factory()->create();
        $user->assignRole('User');

        $response = $this->actingAs($user)->get(route('queue.display', $slot->service_id));

        $response->assertOk();
    }

    public function test_user_can_cancel_their_ticket(): void
    {
        $user = User::factory()->create();
        $user->assignRole('User');

        $slot = $this->createServiceSlot();
        $ticket = QueueTicket::create([
            'user_id' => $user->id,
            'service_slot_id' => $slot->id,
            'queue_number' => '001',
            'booking_code' => 'TESTCODE',
            'estimated_wait' => 0,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($user)->post(route('queue.tickets.cancel', $ticket));

        $response->assertRedirect(route('queue.tickets'));
        $response->assertSessionHas('success');
        $this->assertEquals('batal', $ticket->fresh()->status);
    }

    public function test_admin_can_cancel_any_ticket(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $user = User::factory()->create();
        $user->assignRole('User');

        $slot = $this->createServiceSlot();
        $ticket = QueueTicket::create([
            'user_id' => $user->id,
            'service_slot_id' => $slot->id,
            'queue_number' => '001',
            'booking_code' => 'TESTCODE',
            'estimated_wait' => 0,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($admin)->post(route('queue.tickets.cancel', $ticket));

        $response->assertRedirect(route('queue.tickets'));
        $response->assertSessionHas('success');
        $this->assertEquals('batal', $ticket->fresh()->status);
    }

    public function test_user_cannot_cancel_others_ticket(): void
    {
        $userA = User::factory()->create();
        $userA->assignRole('User');

        $userB = User::factory()->create();
        $userB->assignRole('User');

        $slot = $this->createServiceSlot();
        $ticket = QueueTicket::create([
            'user_id' => $userA->id,
            'service_slot_id' => $slot->id,
            'queue_number' => '001',
            'booking_code' => 'TESTCODE',
            'estimated_wait' => 0,
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($userB)->post(route('queue.tickets.cancel', $ticket));

        $response->assertForbidden();
        $this->assertEquals('menunggu', $ticket->fresh()->status);
    }

    public function test_queue_current_returns_json(): void
    {
        $slot = $this->createServiceSlot();

        $response = $this->get(route('queue.current', $slot->service_id));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }

    public function test_auto_generates_slots_when_none_exist_for_date(): void
    {
        $institution = Institution::create([
            'name' => 'Test Institution',
            'description' => 'Test Description',
            'address' => 'Test Address',
            'is_active' => true,
        ]);

        $service = Service::create([
            'institution_id' => $institution->id,
            'name' => 'Test Service',
            'description' => 'Test Service Description',
            'duration' => 15,
            'daily_quota' => 100,
            'is_active' => true,
        ]);

        $targetDate = '2026-12-25';

        // Ensure no slots exist yet
        $this->assertEquals(0, ServiceSlot::where('service_id', $service->id)->whereDate('date', $targetDate)->count());

        $user = User::factory()->create();
        $user->assignRole('User');

        $response = $this->actingAs($user)->get("/api/services/{$service->id}/slots?date={$targetDate}");

        $response->assertOk();
        $slotCount = ServiceSlot::where('service_id', $service->id)->whereDate('date', $targetDate)->count();
        $this->assertGreaterThanOrEqual(2, $slotCount);
        $this->assertLessThanOrEqual(5, $slotCount);
    }

    public function test_reuses_existing_slots_on_subsequent_calls(): void
    {
        $institution = Institution::create([
            'name' => 'Test Institution',
            'description' => 'Test Description',
            'address' => 'Test Address',
            'is_active' => true,
        ]);

        $service = Service::create([
            'institution_id' => $institution->id,
            'name' => 'Test Service',
            'description' => 'Test Service Description',
            'duration' => 15,
            'daily_quota' => 100,
            'is_active' => true,
        ]);

        $targetDate = '2026-12-25';

        $user = User::factory()->create();
        $user->assignRole('User');

        // First call to generate
        $this->actingAs($user)->get("/api/services/{$service->id}/slots?date={$targetDate}");
        $firstSlotCount = ServiceSlot::where('service_id', $service->id)->whereDate('date', $targetDate)->count();
        $this->assertGreaterThanOrEqual(2, $firstSlotCount);

        // Second call should reuse and not duplicate
        $this->actingAs($user)->get("/api/services/{$service->id}/slots?date={$targetDate}");
        $secondSlotCount = ServiceSlot::where('service_id', $service->id)->whereDate('date', $targetDate)->count();
        $this->assertEquals($firstSlotCount, $secondSlotCount);
    }

    public function test_auto_generated_slots_are_varied_by_service(): void
    {
        $institution = Institution::create([
            'name' => 'Test Institution',
            'description' => 'Test Description',
            'address' => 'Test Address',
            'is_active' => true,
        ]);

        $queueService = app(\App\Services\QueueService::class);
        $date = '2026-12-25';

        $slotConfigurations = [];

        for ($i = 0; $i < 5; $i++) {
            $service = Service::create([
                'institution_id' => $institution->id,
                'name' => "Service $i",
                'description' => 'Desc',
                'duration' => 15,
                'daily_quota' => 100,
                'is_active' => true,
            ]);

            $slots = $queueService->getAvailableSlots($service, $date);
            $this->assertGreaterThanOrEqual(2, count($slots));
            $this->assertLessThanOrEqual(5, count($slots));

            // Map slots to a string representation of start times to compare variety
            $times = $slots->map(fn($s) => $s->start_time->format('H:i'))->toArray();
            $slotConfigurations[] = implode(',', $times);
        }

        // Check that not all generated slot configurations are identical (at least some variety exists)
        $uniqueConfigurations = array_unique($slotConfigurations);
        $this->assertGreaterThan(1, count($uniqueConfigurations), "Generated slots should have variety and not all be identical");
    }

    public function test_admin_can_create_service_slot(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $institution = Institution::create([
            'name' => 'Test Institution',
            'description' => 'Test Description',
            'address' => 'Test Address',
            'is_active' => true,
        ]);

        $service = Service::create([
            'institution_id' => $institution->id,
            'name' => 'Test Service',
            'description' => 'Test Service Description',
            'duration' => 15,
            'daily_quota' => 100,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.services.slots.store', $service), [
            'date' => now()->addDay()->toDateString(),
            'start_time' => '08:00',
            'end_time' => '10:00',
            'quota' => 20,
        ]);

        $response->assertRedirect(route('admin.services.slots.index', $service->id));
        $this->assertDatabaseHas('service_slots', [
            'service_id' => $service->id,
            'start_time' => '08:00',
            'end_time' => '10:00',
            'quota' => 20,
        ]);
    }

    public function test_admin_can_delete_service_slot(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $slot = $this->createServiceSlot();

        $response = $this->actingAs($admin)->delete(route('admin.slots.destroy', $slot));

        $response->assertRedirect(route('admin.services.slots.index', $slot->service_id));
        $this->assertDatabaseMissing('service_slots', [
            'id' => $slot->id,
        ]);
    }
}
