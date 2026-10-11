<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating fake HealthWorker records.
 */
class HealthWorkerFactory extends Factory
{
    public function definition(): array
    {
        $position = $this->faker->randomElement(['Doctor', 'Nurse', 'Midwife', 'Barangay Health Worker']);

        return [
            'first_name'     => $this->faker->firstName(),
            'last_name'      => $this->faker->lastName(),
            'position'       => $position,
            // Only doctors typically have a specialization
            'specialization' => $position === 'Doctor'
                ? $this->faker->randomElement(['General Medicine', 'Pediatrics', 'OB-GYN', 'Internal Medicine'])
                : null,
            'contact_number' => '09' . $this->faker->numerify('#########'),
            'email'          => $this->faker->unique()->safeEmail(),
            'status'         => $this->faker->randomElement(['active', 'active', 'inactive']), // mostly active
        ];
    }
}
