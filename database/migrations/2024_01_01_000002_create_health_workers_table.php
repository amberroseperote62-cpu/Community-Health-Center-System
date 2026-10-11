<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the health_workers table for clinic staff.
     */
    public function up(): void
    {
        Schema::create('health_workers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            // Position defines the worker's role at the health center
            $table->enum('position', ['Doctor', 'Nurse', 'Midwife', 'Barangay Health Worker']);
            $table->string('specialization')->nullable(); // Optional for non-doctors
            $table->string('contact_number', 20);
            $table->string('email')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_workers');
    }
};
