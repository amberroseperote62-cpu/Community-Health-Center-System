<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the appointments table linking patients to health workers.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            // Foreign keys to link appointment with patient and health worker
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('health_worker_id')->constrained()->onDelete('cascade');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->text('reason');
            // Status tracks appointment lifecycle
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Unique constraint prevents double-booking same health worker at same date/time
            $table->unique(['health_worker_id', 'appointment_date', 'appointment_time'], 'no_double_booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
