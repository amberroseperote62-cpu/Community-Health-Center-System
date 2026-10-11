<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\HealthWorker;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating fake Consultation records.
 */
class ConsultationFactory extends Factory
{
    public function definition(): array
    {
        $appointment = Appointment::whereDoesntHave('consultation')->inRandomOrder()->first();

        return [
            'appointment_id'    => $appointment?->id,
            'patient_id'        => $appointment?->patient_id ?? Patient::inRandomOrder()->first()?->id,
            'health_worker_id'  => $appointment?->health_worker_id ?? HealthWorker::inRandomOrder()->first()?->id,
            'consultation_date' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'symptoms'          => $this->faker->randomElement([
                'Fever, cough, and colds', 'Abdominal pain and vomiting',
                'Headache and dizziness', 'Skin rash and itching',
                'High blood pressure', 'Difficulty breathing', 'Joint pain',
            ]),
            'diagnosis' => $this->faker->randomElement([
                'Upper Respiratory Tract Infection', 'Hypertension',
                'Gastroenteritis', 'Dermatitis', 'Asthma',
                'Urinary Tract Infection', 'Dengue Fever',
            ]),
            'treatment_notes' => $this->faker->randomElement([
                'Prescribed antibiotics and advised rest for 3 days.',
                'Blood pressure medication adjusted. Follow up in 2 weeks.',
                'ORS and diet modification. Monitor for 24 hours.',
                'Topical cream applied. Avoid allergens.',
                'Nebulization done. Prescribed bronchodilator.',
            ]),
        ];
    }
}
