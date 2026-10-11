<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\HealthWorker;
use App\Models\Patient;

/**
 * Resource controller for managing appointments.
 */
class AppointmentController extends Controller
{
    /**
     * Display a filtered, paginated list of appointments.
     * Supports filtering by status and date.
     */
    public function index()
    {
        $status = request('status');
        $date   = request('date');
        $search = request('search');

        $appointments = Appointment::with(['patient', 'healthWorker'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($date, fn($q) => $q->whereDate('appointment_date', $date))
            ->when($search, function ($q, $search) {
                $q->whereHas('patient', function ($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(10)
            ->withQueryString();

        return view('appointments.index', compact('appointments', 'status', 'date', 'search'));
    }

    public function create()
    {
        // Pass patients and active health workers to populate dropdowns
        $patients      = Patient::orderBy('last_name')->get();
        $healthWorkers = HealthWorker::where('status', 'active')->orderBy('last_name')->get();

        return view('appointments.create', compact('patients', 'healthWorkers'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        Appointment::create($request->validated());

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'healthWorker', 'consultation']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients      = Patient::orderBy('last_name')->get();
        $healthWorkers = HealthWorker::where('status', 'active')->orderBy('last_name')->get();

        return view('appointments.edit', compact('appointment', 'patients', 'healthWorkers'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled and removed.');
    }
}
