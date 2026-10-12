<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\HealthWorker;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Runs all seeders in the correct order to satisfy foreign key constraints.
     */
    public function run(): void
    {
        // ---- 1. Create User accounts ----
        // Admin user: full access to all features including delete
        User::factory()->create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Staff user: can view/create/edit but not delete
        User::factory()->create([
            'name'     => 'Staff User',
            'email'    => 'staff@example.com',
            'password' => bcrypt('password'),
            'role'     => 'staff',
        ]);

        // ---- 2. Create Health Workers ----
        HealthWorker::factory(8)->create();

        // ---- 3. Create Patients ----
        Patient::factory(20)->create();

        // ---- 4. Create Medicines ----
        Medicine::factory(15)->create();

        // ---- 5. Create Appointments ----
        // We create them one at a time to avoid unique constraint violations
        $created = 0;
        $attempts = 0;
        while ($created < 30 && $attempts < 200) {
            $attempts++;
            $patient      = Patient::inRandomOrder()->first();
            $healthWorker = HealthWorker::inRandomOrder()->first();
            $date         = now()->subDays(rand(0, 90))->addDays(rand(0, 30))->format('Y-m-d');
            $times        = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
            $time         = $times[array_rand($times)];

            // Skip if this health worker is already booked at this date/time
            $exists = Appointment::where('health_worker_id', $healthWorker->id)
                ->where('appointment_date', $date)
                ->where('appointment_time', $time)
                ->exists();

            if (!$exists) {
                Appointment::create([
                    'patient_id'       => $patient->id,
                    'health_worker_id' => $healthWorker->id,
                    'appointment_date' => $date,
                    'appointment_time' => $time,
                    'reason'           => collect([
                        'Regular check-up', 'Follow-up visit', 'Fever and cough',
                        'Hypertension management', 'Prenatal check', 'Vaccination',
                        'Wound dressing', 'Stomach pain', 'Headache',
                    ])->random(),
                    'status' => collect(['pending', 'confirmed', 'completed', 'cancelled'])->random(),
                ]);
                $created++;
            }
        }

        // ---- 6. Create Consultations ----
        // Link consultations to completed appointments
        $completedAppointments = Appointment::where('status', 'completed')
            ->inRandomOrder()
            ->limit(15)
            ->get();

        $consultationCount = 0;
        foreach ($completedAppointments as $appt) {
            if ($consultationCount >= 15) break;

            $medicine = Medicine::where('stock_quantity', '>', 10)->inRandomOrder()->first();
            $qty = $medicine ? rand(1, 5) : null;

            Consultation::create([
                'appointment_id'      => $appt->id,
                'patient_id'          => $appt->patient_id,
                'health_worker_id'    => $appt->health_worker_id,
                'medicine_id'         => $medicine?->id,
                'prescribed_quantity' => $qty,
                'dosage_instructions' => $medicine ? collect([
                    '1 tablet 3x a day for 7 days',
                    '1 capsule twice daily',
                    '5ml syrup every 6 hours',
                    '1 tablet as needed for pain',
                    'Apply thin layer twice daily',
                ])->random() : null,
                'consultation_date'   => $appt->appointment_date,
                'symptoms' => collect([
                    'Fever, cough, and colds', 'Abdominal pain', 'Headache and dizziness',
                    'Skin rash', 'High blood pressure', 'Joint pain',
                ])->random(),
                'diagnosis' => collect([
                    'Upper Respiratory Tract Infection', 'Hypertension',
                    'Gastroenteritis', 'Dermatitis', 'Asthma',
                ])->random(),
                'treatment_notes' => collect([
                    'Prescribed antibiotics and advised rest for 3 days.',
                    'Blood pressure medication adjusted. Follow up in 2 weeks.',
                    'ORS and diet modification.',
                    'Topical cream applied.',
                    'Nebulization done.',
                ])->random(),
            ]);

            if ($medicine && $qty) {
                $medicine->decrement('stock_quantity', $qty);
            }

            $consultationCount++;
        }
    }
}
