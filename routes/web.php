<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthWorkerController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All application routes are protected by the 'auth' middleware.
| Delete operations are further restricted to 'admin' role only.
|
*/

// Redirect root to dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

// ---- Authenticated Routes ----
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Breeze profile routes (edit, update, delete account)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ---- Patients (with soft-delete protection on destroy) ----
    Route::resource('patients', PatientController::class)->except(['destroy']);
    Route::delete('patients/{patient}', [PatientController::class, 'destroy'])
        ->name('patients.destroy')
        ->middleware('admin');

    // ---- Health Workers ----
    Route::resource('health-workers', HealthWorkerController::class)->except(['destroy']);
    Route::delete('health-workers/{health_worker}', [HealthWorkerController::class, 'destroy'])
        ->name('health-workers.destroy')
        ->middleware('admin');

    // ---- Appointments ----
    Route::resource('appointments', AppointmentController::class)->except(['destroy']);
    Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])
        ->name('appointments.destroy')
        ->middleware('admin');

    // ---- Consultations ----
    Route::resource('consultations', ConsultationController::class)->except(['destroy']);
    Route::delete('consultations/{consultation}', [ConsultationController::class, 'destroy'])
        ->name('consultations.destroy')
        ->middleware('admin');

    // ---- Medicines ----
    Route::resource('medicines', MedicineController::class)->except(['destroy']);
    Route::delete('medicines/{medicine}', [MedicineController::class, 'destroy'])
        ->name('medicines.destroy')
        ->middleware('admin');
});

// Breeze auth routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';
