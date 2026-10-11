<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating fake Medicine/inventory records.
 */
class MedicineFactory extends Factory
{
    // Pre-defined medicine data for realism
    private static array $medicines = [
        ['name' => 'Amoxicillin', 'generic' => 'Amoxicillin', 'category' => 'Antibiotic', 'form' => 'capsule'],
        ['name' => 'Paracetamol', 'generic' => 'Paracetamol', 'category' => 'Analgesic', 'form' => 'tablet'],
        ['name' => 'Ibuprofen', 'generic' => 'Ibuprofen', 'category' => 'NSAID', 'form' => 'tablet'],
        ['name' => 'Cetirizine', 'generic' => 'Cetirizine HCl', 'category' => 'Antihistamine', 'form' => 'tablet'],
        ['name' => 'Salbutamol Syrup', 'generic' => 'Salbutamol', 'category' => 'Bronchodilator', 'form' => 'syrup'],
        ['name' => 'Metformin', 'generic' => 'Metformin HCl', 'category' => 'Antidiabetic', 'form' => 'tablet'],
        ['name' => 'Amlodipine', 'generic' => 'Amlodipine Besylate', 'category' => 'Antihypertensive', 'form' => 'tablet'],
        ['name' => 'Vitamin C 500mg', 'generic' => 'Ascorbic Acid', 'category' => 'Vitamin', 'form' => 'tablet'],
        ['name' => 'ORS Sachets', 'generic' => 'Oral Rehydration Salts', 'category' => 'Electrolyte', 'form' => 'other'],
        ['name' => 'Cotrimoxazole', 'generic' => 'Sulfamethoxazole+Trimethoprim', 'category' => 'Antibiotic', 'form' => 'tablet'],
        ['name' => 'Ferrous Sulfate', 'generic' => 'Ferrous Sulfate', 'category' => 'Supplement', 'form' => 'tablet'],
        ['name' => 'Folic Acid', 'generic' => 'Folic Acid', 'category' => 'Vitamin', 'form' => 'tablet'],
        ['name' => 'Betamethasone Cream', 'generic' => 'Betamethasone', 'category' => 'Corticosteroid', 'form' => 'ointment'],
        ['name' => 'Omeprazole', 'generic' => 'Omeprazole', 'category' => 'Antacid', 'form' => 'capsule'],
        ['name' => 'Losartan', 'generic' => 'Losartan Potassium', 'category' => 'Antihypertensive', 'form' => 'tablet'],
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $med = self::$medicines[self::$index % count(self::$medicines)];
        self::$index++;

        $stock    = $this->faker->numberBetween(0, 200);
        $reorder  = $this->faker->numberBetween(10, 30);

        return [
            'name'           => $med['name'],
            'generic_name'   => $med['generic'],
            'category'       => $med['category'],
            'dosage_form'    => $med['form'],
            'stock_quantity' => $stock,
            'reorder_level'  => $reorder,
            // Some medicines near/past expiry for demo purposes
            'expiry_date'    => $this->faker->dateTimeBetween('-1 month', '+2 years')->format('Y-m-d'),
        ];
    }
}
