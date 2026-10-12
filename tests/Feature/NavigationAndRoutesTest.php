<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\HealthWorker;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationAndRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_protected_routes(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('patients.index'))->assertRedirect(route('login'));
        $this->get(route('health-workers.index'))->assertRedirect(route('login'));
        $this->get(route('appointments.index'))->assertRedirect(route('login'));
        $this->get(route('consultations.index'))->assertRedirect(route('login'));
        $this->get(route('medicines.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_all_module_routes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->actingAs($user)->get(route('patients.index'))->assertOk();
        $this->actingAs($user)->get(route('health-workers.index'))->assertOk();
        $this->actingAs($user)->get(route('appointments.index'))->assertOk();
        $this->actingAs($user)->get(route('consultations.index'))->assertOk();
        $this->actingAs($user)->get(route('medicines.index'))->assertOk();
    }

    public function test_dashboard_displays_correct_summary_metrics(): void
    {
        $user = User::factory()->create();

        $patients = Patient::factory(4)->create();
        $hw = HealthWorker::factory()->create(['status' => 'active']);
        $times = ['08:00', '09:00', '10:00', '11:00'];
        foreach ($times as $index => $time) {
            Appointment::create([
                'patient_id' => $patients[$index]->id,
                'health_worker_id' => $hw->id,
                'appointment_date' => today()->toDateString(),
                'appointment_time' => $time,
                'reason' => 'Checkup ' . $index,
                'status' => 'pending',
            ]);
        }

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Total Registered Patients');
        $response->assertSee('Active Health Workers');
        $response->assertSee("Today's Appointments", false);
    }
}
