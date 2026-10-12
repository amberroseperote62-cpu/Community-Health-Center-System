<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\HealthWorker;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $staff;
    private Patient $patient;
    private HealthWorker $healthWorker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->staff = User::factory()->create(['role' => 'staff']);
        $this->patient = Patient::factory()->create();
        $this->healthWorker = HealthWorker::factory()->create(['status' => 'active']);
    }

    public function test_can_view_appointments_list(): void
    {
        $appt = Appointment::factory()->create([
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
        ]);

        $response = $this->actingAs($this->staff)->get(route('appointments.index'));

        $response->assertOk();
        $response->assertSee($this->patient->full_name);
    }

    public function test_can_schedule_new_appointment(): void
    {
        $data = [
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '09:00',
            'reason' => 'Routine prenatal checkup',
            'status' => 'pending',
        ];

        $response = $this->actingAs($this->staff)->post(route('appointments.store'), $data);

        $response->assertRedirect(route('appointments.index'));
        $this->assertDatabaseHas('appointments', [
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
            'reason' => 'Routine prenatal checkup',
        ]);
    }

    public function test_prevents_double_booking_conflict(): void
    {
        $date = now()->addDays(3)->format('Y-m-d');
        $time = '10:00';

        // First appointment booked
        Appointment::create([
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'reason' => 'Initial checkup',
            'status' => 'confirmed',
        ]);

        // Second patient attempts to book same doctor at same date and time
        $secondPatient = Patient::factory()->create();

        $response = $this->actingAs($this->staff)->post(route('appointments.store'), [
            'patient_id' => $secondPatient->id,
            'health_worker_id' => $this->healthWorker->id,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'reason' => 'Conflict slot visit',
            'status' => 'pending',
        ]);

        $response->assertSessionHasErrors(['appointment_time']);
    }

    public function test_can_update_appointment_status(): void
    {
        $appt = Appointment::factory()->create([
            'status' => 'pending',
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
        ]);

        $response = $this->actingAs($this->staff)->put(route('appointments.update', $appt), [
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
            'appointment_date' => $appt->appointment_date->format('Y-m-d'),
            'appointment_time' => $appt->appointment_time,
            'reason' => 'Updated Reason',
            'status' => 'confirmed',
        ]);

        $response->assertRedirect(route('appointments.show', $appt));
        $this->assertDatabaseHas('appointments', ['id' => $appt->id, 'status' => 'confirmed']);
    }

    public function test_admin_can_delete_appointment(): void
    {
        $appt = Appointment::factory()->create([
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('appointments.destroy', $appt));

        $response->assertRedirect(route('appointments.index'));
        $this->assertDatabaseMissing('appointments', ['id' => $appt->id]);
    }
}
