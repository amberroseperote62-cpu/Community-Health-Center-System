<?php

namespace Database\Factories;

use App\Models\HealthWorker;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating fake Appointment records.
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id'       => Patient::inRandomOrder()->first()?->id ?? Patient::factory(),
            'health_worker_id' => HealthWorker::inRandomOrder()->first()?->id ?? HealthWorker::factory(),
            'appointment_date' => $this->faker->dateTimeBetween('-3 months', '+1 month')->format('Y-m-d'),
            'appointment_time' => $this->faker->randomElement(['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00']),
            'reason'           => $this->faker->randomElement([
                'Regular check-up', 'Follow-up visit', 'Fever and cough',
                'Hypertension management', 'Prenatal check', 'Vaccination',
                'Wound dressing', 'Stomach pain', 'Headache', 'Skin rash',
            ]),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
        ];
    }
}
