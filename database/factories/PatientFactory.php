<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating fake Patient records.
 */
class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'birthdate'         => $this->faker->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
            'gender'            => $this->faker->randomElement(['Male', 'Female']),
            'address'           => $this->faker->address(),
            'contact_number'    => '09' . $this->faker->numerify('#########'),
            'emergency_contact' => $this->faker->name() . ' - 09' . $this->faker->numerify('#########'),
        ];
    }
}
