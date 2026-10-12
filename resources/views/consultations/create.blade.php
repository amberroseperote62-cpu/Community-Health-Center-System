@extends('layouts.app')

@section('title', 'Record Consultation')
@section('subtitle', 'Log a clinical encounter, diagnosis, and dispense medicine prescription')

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-clipboard2-pulse text-primary me-2"></i>Medical Consultation &amp; Prescription Form</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('consultations.store') }}" id="create-consult-form">
                    @csrf

                    <!-- Encounter Info Section -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="bi bi-person-check me-1 text-primary"></i> 1. Patient &amp; Attending Staff Details
                    </h6>

                    <div class="row g-3 mb-4">
                        <!-- Patient Select -->
                        <div class="col-md-6">
                            <label for="patient_id" class="form-label fw-semibold">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                <option value="">Select registered patient...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', request('patient_id')) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }} (DOB: {{ $patient->birthdate->format('M d, Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Attending Health Worker -->
                        <div class="col-md-6">
                            <label for="health_worker_id" class="form-label fw-semibold">Attending Health Worker <span class="text-danger">*</span></label>
                            <select name="health_worker_id" id="health_worker_id" class="form-select @error('health_worker_id') is-invalid @enderror" required>
                                <option value="">Select healthcare provider...</option>
                                @foreach($healthWorkers as $hw)
                                    <option value="{{ $hw->id }}" {{ old('health_worker_id', request('health_worker_id')) == $hw->id ? 'selected' : '' }}>
                                        {{ $hw->full_name }} &mdash; {{ $hw->position }}
                                    </option>
                                @endforeach
                            </select>
                            @error('health_worker_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Linked Appointment (Optional) -->
                        <div class="col-md-6">
                            <label for="appointment_id" class="form-label fw-semibold">Linked Appointment <small class="text-muted">(Optional / Walk-in)</small></label>
                            <select name="appointment_id" id="appointment_id" class="form-select @error('appointment_id') is-invalid @enderror">
                                <option value="">None (Walk-in Consultation)</option>
                                @foreach($appointments as $appt)
                                    <option value="{{ $appt->id }}" {{ old('appointment_id', request('appointment_id')) == $appt->id ? 'selected' : '' }}>
                                        #{{ $appt->id }} &mdash; {{ $appt->patient->full_name }} ({{ $appt->appointment_date->format('M d, Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Consultation Date -->
                        <div class="col-md-6">
                            <label for="consultation_date" class="form-label fw-semibold">Consultation Date <span class="text-danger">*</span></label>
                            <input type="date" name="consultation_date" id="consultation_date"
                                value="{{ old('consultation_date', date('Y-m-d')) }}"
                                class="form-control @error('consultation_date') is-invalid @enderror" required>
                            @error('consultation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Clinical Findings Section -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="bi bi-file-medical me-1 text-primary"></i> 2. Clinical Findings &amp; Diagnosis
                    </h6>

                    <div class="row g-3 mb-4">
                        <!-- Symptoms -->
                        <div class="col-12">
                            <label for="symptoms" class="form-label fw-semibold">Reported Symptoms &amp; Vitals <span class="text-danger">*</span></label>
                            <textarea name="symptoms" id="symptoms" rows="2" class="form-control @error('symptoms') is-invalid @enderror"
                                placeholder="e.g. Chief complaint: Fever 38.5C, productive cough for 3 days, body weakness" required>{{ old('symptoms') }}</textarea>
                            @error('symptoms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Diagnosis -->
                        <div class="col-12">
                            <label for="diagnosis" class="form-label fw-semibold">Clinical Diagnosis <span class="text-danger">*</span></label>
                            <textarea name="diagnosis" id="diagnosis" rows="2" class="form-control @error('diagnosis') is-invalid @enderror"
                                placeholder="e.g. Acute Upper Respiratory Tract Infection (URTI)" required>{{ old('diagnosis') }}</textarea>
                            @error('diagnosis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Treatment Notes -->
                        <div class="col-12">
                            <label for="treatment_notes" class="form-label fw-semibold">Treatment Notes &amp; Management Plan <span class="text-danger">*</span></label>
                            <textarea name="treatment_notes" id="treatment_notes" rows="3" class="form-control @error('treatment_notes') is-invalid @enderror"
                                placeholder="e.g. Advised adequate hydration and rest. Prescribed medication. Follow up in 3 days if fever persists." required>{{ old('treatment_notes') }}</textarea>
                            @error('treatment_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Prescription & Pharmacy Dispensing Section -->
                    <div class="card border border-success border-opacity-25 bg-success bg-opacity-10 mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-capsule text-success fs-5"></i>
                                <h6 class="fw-bold text-success mb-0">3. Prescription &amp; Pharmacy Dispensing (Optional)</h6>
                            </div>
                            <p class="text-muted small mb-3">
                                Selecting a medicine will automatically deduct the prescribed quantity from inventory upon saving.
                            </p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="medicine_id" class="form-label fw-semibold">Dispense Medicine</label>
                                    <select name="medicine_id" id="medicine_id" class="form-select @error('medicine_id') is-invalid @enderror">
                                        <option value="">No medication prescribed</option>
                                        @foreach($medicines as $med)
                                            <option value="{{ $med->id }}" {{ old('medicine_id') == $med->id ? 'selected' : '' }}>
                                                {{ $med->name }} ({{ $med->generic_name }}) &mdash; {{ $med->stock_quantity }} in stock
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('medicine_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="prescribed_quantity" class="form-label fw-semibold">Quantity to Dispense</label>
                                    <input type="number" name="prescribed_quantity" id="prescribed_quantity"
                                        value="{{ old('prescribed_quantity') }}" min="1"
                                        class="form-control @error('prescribed_quantity') is-invalid @enderror"
                                        placeholder="e.g. 10">
                                    @error('prescribed_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="dosage_instructions" class="form-label fw-semibold">Dosage &amp; Usage Instructions</label>
                                    <input type="text" name="dosage_instructions" id="dosage_instructions"
                                        value="{{ old('dosage_instructions') }}"
                                        class="form-control @error('dosage_instructions') is-invalid @enderror"
                                        placeholder="e.g. 1 tablet 3x daily after meals for 7 days">
                                    @error('dosage_instructions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-chc-primary px-4">
                            <i class="bi bi-save me-1"></i> Save Consultation Record
                        </button>
                        <a href="{{ route('consultations.index') }}" class="btn btn-outline-secondary px-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
