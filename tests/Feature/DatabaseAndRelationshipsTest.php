<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\HealthWorker;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseAndRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrations_and_seeders_execute_correctly(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'admin@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'staff@example.com']);
        $this->assertGreaterThan(0, Patient::count());
        $this->assertGreaterThan(0, HealthWorker::count());
        $this->assertGreaterThan(0, Appointment::count());
        $this->assertGreaterThan(0, Medicine::count());
        $this->assertGreaterThan(0, Consultation::count());
    }

    public function test_all_five_model_relationships(): void
    {
        $patient = Patient::factory()->create();
        $hw = HealthWorker::factory()->create();
        $medicine = Medicine::factory()->create(['stock_quantity' => 50]);

        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'health_worker_id' => $hw->id,
        ]);

        $consultation = Consultation::factory()->create([
            'patient_id' => $patient->id,
            'health_worker_id' => $hw->id,
            'appointment_id' => $appointment->id,
            'medicine_id' => $medicine->id,
            'prescribed_quantity' => 5,
        ]);

        // Test relationships
        $this->assertTrue($appointment->patient->is($patient));
        $this->assertTrue($appointment->healthWorker->is($hw));
        $this->assertTrue($consultation->patient->is($patient));
        $this->assertTrue($consultation->healthWorker->is($hw));
        $this->assertTrue($consultation->appointment->is($appointment));
        $this->assertTrue($consultation->medicine->is($medicine));
        $this->assertTrue($patient->appointments->contains($appointment));
        $this->assertTrue($patient->consultations->contains($consultation));
        $this->assertTrue($medicine->consultations->contains($consultation));
    }
}
