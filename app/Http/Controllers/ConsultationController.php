<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultationRequest;
use App\Http\Requests\UpdateConsultationRequest;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\HealthWorker;
use App\Models\Medicine;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

/**
 * Resource controller for managing consultations and prescriptions.
 */
class ConsultationController extends Controller
{
    public function index()
    {
        $search        = request('search');
        $consultations = Consultation::with(['patient', 'healthWorker', 'medicine'])
            ->when($search, function ($q, $search) {
                $q->whereHas('patient', function ($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('consultation_date')
            ->paginate(10)
            ->withQueryString();

        return view('consultations.index', compact('consultations', 'search'));
    }

    public function create()
    {
        $patients      = Patient::orderBy('last_name')->get();
        $healthWorkers = HealthWorker::where('status', 'active')->orderBy('last_name')->get();
        $medicines     = Medicine::where('stock_quantity', '>', 0)->orderBy('name')->get();
        // Appointments that don't yet have a consultation
        $appointments  = Appointment::whereDoesntHave('consultation')
            ->where('status', 'confirmed')
            ->with('patient')
            ->orderByDesc('appointment_date')
            ->get();

        return view('consultations.create', compact('patients', 'healthWorkers', 'medicines', 'appointments'));
    }

    /**
     * Store a new consultation.
     * Uses a DB transaction to ensure stock deduction and record saving are atomic.
     */
    public function store(StoreConsultationRequest $request)
    {
        DB::transaction(function () use ($request) {
            $consultation = Consultation::create($request->validated());

            // Mark linked appointment as completed if provided
            if ($request->appointment_id) {
                Appointment::where('id', $request->appointment_id)
                    ->update(['status' => 'completed']);
            }

            // Deduct from inventory if medicine prescribed
            if ($request->filled('medicine_id') && $request->filled('prescribed_quantity')) {
                Medicine::where('id', $request->medicine_id)
                    ->decrement('stock_quantity', $request->prescribed_quantity);
            }
        });

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation recorded and prescription saved.');
    }

    public function show(Consultation $consultation)
    {
        $consultation->load(['patient', 'healthWorker', 'appointment', 'medicine']);
        return view('consultations.show', compact('consultation'));
    }

    public function edit(Consultation $consultation)
    {
        $patients      = Patient::orderBy('last_name')->get();
        $healthWorkers = HealthWorker::where('status', 'active')->orderBy('last_name')->get();
        $medicines     = Medicine::orderBy('name')->get();
        $appointments  = Appointment::whereDoesntHave('consultation')
            ->orWhere('id', $consultation->appointment_id)
            ->with('patient')
            ->orderByDesc('appointment_date')
            ->get();

        return view('consultations.edit', compact('consultation', 'patients', 'healthWorkers', 'medicines', 'appointments'));
    }

    /**
     * Update a consultation.
     * Restores previous stock and deducts new prescription stock if changed.
     */
    public function update(UpdateConsultationRequest $request, Consultation $consultation)
    {
        DB::transaction(function () use ($request, $consultation) {
            // Restore previously deducted stock
            if ($consultation->medicine_id && $consultation->prescribed_quantity) {
                Medicine::where('id', $consultation->medicine_id)
                    ->increment('stock_quantity', $consultation->prescribed_quantity);
            }

            $consultation->update($request->validated());

            // Deduct new prescription stock
            if ($request->filled('medicine_id') && $request->filled('prescribed_quantity')) {
                Medicine::where('id', $request->medicine_id)
                    ->decrement('stock_quantity', $request->prescribed_quantity);
            }
        });

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Consultation updated successfully.');
    }

    public function destroy(Consultation $consultation)
    {
        DB::transaction(function () use ($consultation) {
            // Restore stock before deleting
            if ($consultation->medicine_id && $consultation->prescribed_quantity) {
                Medicine::where('id', $consultation->medicine_id)
                    ->increment('stock_quantity', $consultation->prescribed_quantity);
            }
            $consultation->delete();
        });

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation record deleted.');
    }
}
