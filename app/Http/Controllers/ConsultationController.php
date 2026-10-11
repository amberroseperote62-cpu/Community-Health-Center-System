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
 * Resource controller for managing consultations.
 * Also handles prescriptions and stock deduction.
 */
class ConsultationController extends Controller
{
    public function index()
    {
        $search        = request('search');
        $consultations = Consultation::with(['patient', 'healthWorker'])
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
            // Create the consultation record
            $consultation = Consultation::create($request->only([
                'appointment_id', 'patient_id', 'health_worker_id',
                'consultation_date', 'symptoms', 'diagnosis', 'treatment_notes',
            ]));

            // Mark linked appointment as completed if provided
            if ($request->appointment_id) {
                Appointment::find($request->appointment_id)
                    ->update(['status' => 'completed']);
            }

            // Attach prescribed medicines and deduct stock
            if ($request->has('medicines') && is_array($request->medicines)) {
                foreach ($request->medicines as $med) {
                    if (empty($med['medicine_id'])) continue;

                    $consultation->medicines()->attach($med['medicine_id'], [
                        'quantity'            => $med['quantity'],
                        'dosage_instructions' => $med['dosage_instructions'],
                    ]);

                    // Deduct from inventory (prevent negative stock)
                    Medicine::find($med['medicine_id'])
                        ->decrement('stock_quantity', $med['quantity']);
                }
            }
        });

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation recorded and prescriptions saved.');
    }

    public function show(Consultation $consultation)
    {
        $consultation->load(['patient', 'healthWorker', 'appointment', 'medicines']);
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

        $consultation->load('medicines');

        return view('consultations.edit', compact('consultation', 'patients', 'healthWorkers', 'medicines', 'appointments'));
    }

    /**
     * Update a consultation.
     * Re-syncs medicines: restores old stock then deducts new quantities.
     */
    public function update(UpdateConsultationRequest $request, Consultation $consultation)
    {
        DB::transaction(function () use ($request, $consultation) {
            $consultation->update($request->only([
                'appointment_id', 'patient_id', 'health_worker_id',
                'consultation_date', 'symptoms', 'diagnosis', 'treatment_notes',
            ]));

            // Restore previously deducted stock before re-syncing
            foreach ($consultation->medicines as $medicine) {
                $medicine->increment('stock_quantity', $medicine->pivot->quantity);
            }

            // Detach all medicines then re-attach with new prescriptions
            $consultation->medicines()->detach();

            if ($request->has('medicines') && is_array($request->medicines)) {
                foreach ($request->medicines as $med) {
                    if (empty($med['medicine_id'])) continue;

                    $consultation->medicines()->attach($med['medicine_id'], [
                        'quantity'            => $med['quantity'],
                        'dosage_instructions' => $med['dosage_instructions'],
                    ]);

                    // Deduct new quantity from inventory
                    Medicine::find($med['medicine_id'])
                        ->decrement('stock_quantity', $med['quantity']);
                }
            }
        });

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Consultation updated successfully.');
    }

    public function destroy(Consultation $consultation)
    {
        // Restore stock before deleting
        foreach ($consultation->medicines as $medicine) {
            $medicine->increment('stock_quantity', $medicine->pivot->quantity);
        }
        $consultation->delete();

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation record deleted.');
    }
}
