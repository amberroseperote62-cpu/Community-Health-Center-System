<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\HealthWorker;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $staff;
    private Patient $patient;
    private HealthWorker $healthWorker;
    private Medicine $medicine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->staff = User::factory()->create(['role' => 'staff']);
        $this->patient = Patient::factory()->create();
        $this->healthWorker = HealthWorker::factory()->create(['status' => 'active']);
        $this->medicine = Medicine::factory()->create(['stock_quantity' => 50]);
    }

    public function test_can_view_consultations_index(): void
    {
        $consult = Consultation::factory()->create([
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
        ]);

        $response = $this->actingAs($this->staff)->get(route('consultations.index'));

        $response->assertOk();
        $response->assertSee($this->patient->full_name);
    }

    public function test_can_store_consultation_with_medicine_and_deduct_stock(): void
    {
        $initialStock = $this->medicine->stock_quantity; // 50
        $prescribedQty = 10;

        $data = [
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
            'consultation_date' => now()->format('Y-m-d'),
            'symptoms' => 'High fever and severe headache',
            'diagnosis' => 'Dengue suspect',
            'treatment_notes' => 'Monitor platelet count and hydration',
            'medicine_id' => $this->medicine->id,
            'prescribed_quantity' => $prescribedQty,
            'dosage_instructions' => '1 tablet every 6 hours',
        ];

        $response = $this->actingAs($this->staff)->post(route('consultations.store'), $data);

        $response->assertRedirect(route('consultations.index'));

        // Check consultation stored
        $this->assertDatabaseHas('consultations', [
            'patient_id' => $this->patient->id,
            'diagnosis' => 'Dengue suspect',
            'medicine_id' => $this->medicine->id,
            'prescribed_quantity' => $prescribedQty,
        ]);

        // Check stock was decremented
        $this->assertEquals($initialStock - $prescribedQty, $this->medicine->fresh()->stock_quantity);
    }

    public function test_can_link_consultation_to_appointment_and_mark_completed(): void
    {
        $appt = Appointment::factory()->create([
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->staff)->post(route('consultations.store'), [
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
            'appointment_id' => $appt->id,
            'consultation_date' => now()->format('Y-m-d'),
            'symptoms' => 'Flu-like symptoms',
            'diagnosis' => 'Viral Influenza',
            'treatment_notes' => 'Bed rest and fluids',
        ]);

        $response->assertRedirect(route('consultations.index'));
        $this->assertEquals('completed', $appt->fresh()->status);
    }

    public function test_deleting_consultation_restores_dispensed_medicine_stock(): void
    {
        $initialStock = $this->medicine->stock_quantity; // 50
        $prescribedQty = 8;

        $consultation = Consultation::factory()->create([
            'patient_id' => $this->patient->id,
            'health_worker_id' => $this->healthWorker->id,
            'medicine_id' => $this->medicine->id,
            'prescribed_quantity' => $prescribedQty,
        ]);

        // Simulate that 8 were deducted when created
        $this->medicine->decrement('stock_quantity', $prescribedQty);
        $this->assertEquals(42, $this->medicine->fresh()->stock_quantity);

        // Delete consultation
        $response = $this->actingAs($this->admin)->delete(route('consultations.destroy', $consultation));

        $response->assertRedirect(route('consultations.index'));
        $this->assertDatabaseMissing('consultations', ['id' => $consultation->id]);

        // Stock must be restored back to 50
        $this->assertEquals($initialStock, $this->medicine->fresh()->stock_quantity);
    }
}
