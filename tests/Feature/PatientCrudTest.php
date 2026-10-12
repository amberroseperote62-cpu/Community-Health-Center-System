<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientCrudTest extends TestCase
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

    public function test_can_view_patients_index_and_search(): void
    {
        $patient1 = Patient::factory()->create(['first_name' => 'Maria', 'last_name' => 'Santos']);
        $patient2 = Patient::factory()->create(['first_name' => 'Pedro', 'last_name' => 'Reyes']);

        $response = $this->actingAs($this->staff)->get(route('patients.index', ['search' => 'Maria']));

        $response->assertOk();
        $response->assertSee('Maria');
        $response->assertDontSee('Pedro');
    }

    public function test_can_view_patient_create_form(): void
    {
        $response = $this->actingAs($this->staff)->get(route('patients.create'));
        $response->assertOk();
        $response->assertSee('Register Patient');
    }

    public function test_can_store_new_patient(): void
    {
        $data = [
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'birthdate' => '1995-05-15',
            'gender' => 'Male',
            'contact_number' => '09123456789',
            'emergency_contact' => 'Maria Dela Cruz - 09987654321',
            'address' => 'Barangay 1, Manila',
        ];

        $response = $this->actingAs($this->staff)->post(route('patients.store'), $data);

        $response->assertRedirect(route('patients.index'));
        $this->assertDatabaseHas('patients', [
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'contact_number' => '09123456789',
        ]);
    }

    public function test_patient_validation_fails_when_fields_are_missing(): void
    {
        $response = $this->actingAs($this->staff)->post(route('patients.store'), []);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'birthdate', 'gender', 'contact_number', 'address']);
    }

    public function test_can_view_patient_details(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->staff)->get(route('patients.show', $patient));

        $response->assertOk();
        $response->assertSee($patient->full_name);
    }

    public function test_can_update_patient(): void
    {
        $patient = Patient::factory()->create(['first_name' => 'OldName']);

        $response = $this->actingAs($this->staff)->put(route('patients.update', $patient), [
            'first_name' => 'NewName',
            'last_name' => $patient->last_name,
            'birthdate' => $patient->birthdate->format('Y-m-d'),
            'gender' => $patient->gender,
            'contact_number' => '09999999999',
            'address' => 'Updated Address',
        ]);

        $response->assertRedirect(route('patients.show', $patient));
        $this->assertDatabaseHas('patients', ['id' => $patient->id, 'first_name' => 'NewName']);
    }

    public function test_admin_can_soft_delete_patient(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('patients.destroy', $patient));

        $response->assertRedirect(route('patients.index'));
        $this->assertSoftDeleted('patients', ['id' => $patient->id]);
    }

    public function test_staff_cannot_delete_patient(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->staff)->delete(route('patients.destroy', $patient));

        $response->assertForbidden();
        $this->assertDatabaseHas('patients', ['id' => $patient->id]);
    }
}
