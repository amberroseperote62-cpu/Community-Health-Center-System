<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicineCrudTest extends TestCase
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

    public function test_can_view_medicine_catalog_and_search(): void
    {
        $med1 = Medicine::factory()->create(['name' => 'Amoxicillin 500mg', 'category' => 'Antibiotic']);
        $med2 = Medicine::factory()->create(['name' => 'Cetirizine 10mg', 'category' => 'Antihistamine']);

        $response = $this->actingAs($this->staff)->get(route('medicines.index', ['search' => 'Amoxicillin']));

        $response->assertOk();
        $response->assertSee('Amoxicillin 500mg');
        $response->assertDontSee('Cetirizine 10mg');
    }

    public function test_can_store_medicine(): void
    {
        $data = [
            'name' => 'Ibuprofen 400mg',
            'generic_name' => 'Ibuprofen',
            'category' => 'Analgesic',
            'dosage_form' => 'tablet',
            'stock_quantity' => 100,
            'reorder_level' => 20,
            'expiry_date' => now()->addYear()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->staff)->post(route('medicines.store'), $data);

        $response->assertRedirect(route('medicines.index'));
        $this->assertDatabaseHas('medicines', [
            'name' => 'Ibuprofen 400mg',
            'stock_quantity' => 100,
        ]);
    }

    public function test_can_update_medicine_stock(): void
    {
        $med = Medicine::factory()->create(['stock_quantity' => 15]);

        $response = $this->actingAs($this->staff)->put(route('medicines.update', $med), [
            'name' => $med->name,
            'generic_name' => $med->generic_name,
            'category' => $med->category,
            'dosage_form' => $med->dosage_form,
            'stock_quantity' => 60,
            'reorder_level' => 10,
            'expiry_date' => $med->expiry_date->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('medicines.show', $med));
        $this->assertDatabaseHas('medicines', ['id' => $med->id, 'stock_quantity' => 60]);
    }

    public function test_low_stock_and_expiry_detection(): void
    {
        $lowMed = Medicine::factory()->create([
            'stock_quantity' => 5,
            'reorder_level' => 10,
            'expiry_date' => now()->addMonths(6),
        ]);
        $this->assertTrue($lowMed->isLowStock());

        $expiredMed = Medicine::factory()->create([
            'expiry_date' => now()->subDay(),
        ]);
        $this->assertTrue($expiredMed->isExpired());

        $nearExpiryMed = Medicine::factory()->create([
            'expiry_date' => now()->addDays(15),
        ]);
        $this->assertTrue($nearExpiryMed->isNearExpiry());
    }

    public function test_admin_can_delete_medicine(): void
    {
        $med = Medicine::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('medicines.destroy', $med));

        $response->assertRedirect(route('medicines.index'));
        $this->assertDatabaseMissing('medicines', ['id' => $med->id]);
    }

    public function test_staff_cannot_delete_medicine(): void
    {
        $med = Medicine::factory()->create();

        $response = $this->actingAs($this->staff)->delete(route('medicines.destroy', $med));

        $response->assertForbidden();
        $this->assertDatabaseHas('medicines', ['id' => $med->id]);
    }
}
