<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\HealthWorker;
use App\Models\Medicine;
use App\Models\Patient;

/**
 * Dashboard controller — shows summary statistics and alerts.
 */
class DashboardController extends Controller
{
    public function index()
    {
        // Count totals for the summary cards
        $totalPatients      = Patient::count();
        $totalHealthWorkers = HealthWorker::where('status', 'active')->count();
        $todayAppointments  = Appointment::whereDate('appointment_date', today())->count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();

        // Low stock: medicines at or below their reorder level
        $lowStockMedicines = Medicine::whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orderBy('stock_quantity')
            ->get();

        // Expiring soon: non-expired medicines expiring within 30 days
        $expiringSoonMedicines = Medicine::where('expiry_date', '>=', today())
            ->where('expiry_date', '<=', today()->addDays(30))
            ->orderBy('expiry_date')
            ->get();

        // Expired medicines
        $expiredMedicines = Medicine::where('expiry_date', '<', today())->get();

        // Today's appointments with patient and health worker names
        $todayAppointmentList = Appointment::with(['patient', 'healthWorker'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        return view('dashboard', compact(
            'totalPatients',
            'totalHealthWorkers',
            'todayAppointments',
            'pendingAppointments',
            'lowStockMedicines',
            'expiringSoonMedicines',
            'expiredMedicines',
            'todayAppointmentList'
        ));
    }
}
