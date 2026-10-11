<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;

/**
 * Resource controller for managing patients.
 * All routes are protected by the 'auth' middleware (set in routes/web.php).
 */
class PatientController extends Controller
{
    /**
     * Display a searchable, paginated list of all patients.
     */
    public function index()
    {
        // Search by first or last name
        $search   = request('search');
        $patients = Patient::when($search, function ($query, $search) {
            $query->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
        })
        ->orderBy('last_name')
        ->paginate(10)
        ->withQueryString(); // Keep search term in pagination links

        return view('patients.index', compact('patients', 'search'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created patient in the database.
     */
    public function store(StorePatientRequest $request)
    {
        Patient::create($request->validated());

        return redirect()->route('patients.index')
            ->with('success', 'Patient registered successfully.');
    }

    /**
     * Display the full profile of a specific patient,
     * including their appointment and consultation history.
     */
    public function show(Patient $patient)
    {
        // Load related records for the patient profile page
        $patient->load([
            'appointments.healthWorker',
            'consultations.healthWorker',
            'consultations.medicines',
        ]);

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing a patient's record.
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in the database.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $patient->update($request->validated());

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient record updated successfully.');
    }

    /**
     * Soft-delete the patient (records are hidden, not permanently removed).
     * Only admins can delete (enforced by 'admin' middleware on route).
     */
    public function destroy(Patient $patient)
    {
        $patient->delete(); // This is a soft delete

        return redirect()->route('patients.index')
            ->with('success', 'Patient record deleted successfully.');
    }
}
