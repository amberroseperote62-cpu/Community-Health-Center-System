<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the consultation_medicine pivot table for prescriptions.
     * When a prescription is saved, stock is deducted in the controller.
     */
    public function up(): void
    {
        Schema::create('consultation_medicine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // How many units prescribed
            $table->string('dosage_instructions'); // e.g., "1 tablet 3x a day for 7 days"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_medicine');
    }
};
