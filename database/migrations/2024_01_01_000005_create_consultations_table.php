<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the consultations table for medical visit records and prescribed medication.
     */
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            // Appointment is optional (walk-ins may not have a prior appointment)
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('health_worker_id')->constrained()->onDelete('cascade');
            // Prescribed medicine from inventory (optional, direct relationship among the 5 topic tables)
            $table->foreignId('medicine_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('prescribed_quantity')->nullable();
            $table->string('dosage_instructions')->nullable();
            $table->date('consultation_date');
            $table->text('symptoms');
            $table->text('diagnosis');
            $table->text('treatment_notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
