<?php

namespace Tests\Feature;

use App\Models\HealthWorker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthWorkerCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->staff = User::factory()->create(['role' => 'staff']);
    }

    public function test_can_view_health_workers_directory_and_search(): void
    {
        $hw1 = HealthWorker::factory()->create(['first_name' => 'Elena', 'last_name' => 'Ramos', 'position' => 'Doctor']);
        $hw2 = HealthWorker::factory()->create(['first_name' => 'Carlos', 'last_name' => 'Torres', 'position' => 'Nurse']);

        $response = $this->actingAs($this->staff)->get(route('health-workers.index', ['search' => 'Elena']));

        $response->assertOk();
        $response->assertSee('Elena');
        $response->assertDontSee('Carlos');
    }

    public function test_can_store_health_worker(): void
    {
        $data = [
            'first_name' => 'Ana',
            'last_name' => 'Lim',
            'position' => 'Doctor',
            'specialization' => 'Internal Medicine',
            'contact_number' => '09171234567',
            'email' => 'ana.lim@chc.local',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->staff)->post(route('health-workers.store'), $data);

        $response->assertRedirect(route('health-workers.index'));
        $this->assertDatabaseHas('health_workers', [
            'email' => 'ana.lim@chc.local',
            'position' => 'Doctor',
        ]);
    }

    public function test_cannot_store_duplicate_email(): void
    {
        HealthWorker::factory()->create(['email' => 'existing@chc.local']);

        $response = $this->actingAs($this->staff)->post(route('health-workers.store'), [
            'first_name' => 'Test',
            'last_name' => 'User',
            'position' => 'Nurse',
            'contact_number' => '09123456789',
            'email' => 'existing@chc.local',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_can_update_health_worker(): void
    {
        $hw = HealthWorker::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->staff)->put(route('health-workers.update', $hw), [
            'first_name' => $hw->first_name,
            'last_name' => $hw->last_name,
            'position' => $hw->position,
            'specialization' => 'Cardiology',
            'contact_number' => $hw->contact_number,
            'email' => $hw->email,
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('health-workers.show', $hw));
        $this->assertDatabaseHas('health_workers', [
            'id' => $hw->id,
            'specialization' => 'Cardiology',
            'status' => 'inactive',
        ]);
    }

    public function test_admin_can_delete_health_worker(): void
    {
        $hw = HealthWorker::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('health-workers.destroy', $hw));

        $response->assertRedirect(route('health-workers.index'));
        $this->assertDatabaseMissing('health_workers', ['id' => $hw->id]);
    }

    public function test_staff_cannot_delete_health_worker(): void
    {
        $hw = HealthWorker::factory()->create();

        $response = $this->actingAs($this->staff)->delete(route('health-workers.destroy', $hw));

        $response->assertForbidden();
        $this->assertDatabaseHas('health_workers', ['id' => $hw->id]);
    }
}
