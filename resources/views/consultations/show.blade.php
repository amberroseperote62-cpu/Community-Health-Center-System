@extends('layouts.app')

@section('title', 'Consultation Record')
@section('subtitle', 'Consultation #' . $consultation->id . ' &middot; ' . $consultation->patient->full_name . ' (' . $consultation->consultation_date->format('M d, Y') . ')')

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <!-- Main Clinical Record Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-medical-fill text-primary fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Clinical Encounter Details</h5>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('consultations.edit', $consultation) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Edit Record
                    </a>
                    @if(auth()->user()->isAdmin())
                    <button type="button" class="btn btn-outline-danger btn-sm"
                        data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                        data-delete-url="{{ route('consultations.destroy', $consultation) }}"
                        data-delete-msg="Are you sure you want to delete consultation #{{ $consultation->id }}? Any dispensed medicine stock will be automatically returned to inventory.">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Patient</small>
                        <a href="{{ route('patients.show', $consultation->patient) }}" class="fw-bold text-dark text-decoration-none fs-6">
                            {{ $consultation->patient->full_name }}
                        </a>
                        <div class="small text-muted">DOB: {{ $consultation->patient->birthdate->format('M d, Y') }} ({{ $consultation->patient->age }} y/o)</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Attending Healthcare Provider</small>
                        <a href="{{ route('health-workers.show', $consultation->healthWorker) }}" class="fw-bold text-dark text-decoration-none fs-6">
                            {{ $consultation->healthWorker->full_name }}
                        </a>
                        <div class="small text-muted">{{ $consultation->healthWorker->position }} &middot; {{ $consultation->healthWorker->specialization ?? 'General Care' }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Date of Visit</small>
                        <div class="fw-semibold text-primary fs-6"><i class="bi bi-calendar3 me-1"></i>{{ $consultation->consultation_date->format('F d, Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Encounter Type / Appointment</small>
                        @if($consultation->appointment)
                            <a href="{{ route('appointments.show', $consultation->appointment) }}" class="badge bg-primary text-decoration-none p-2 fs-6">
                                <i class="bi bi-calendar-check me-1"></i> Scheduled Appointment #{{ $consultation->appointment->id }}
                            </a>
                        @else
                            <span class="badge bg-secondary p-2 fs-6">
                                <i class="bi bi-person-walking me-1"></i> Walk-in Visit
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Clinical Documentation Blocks -->
                <div class="mb-3">
                    <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.8rem;">Chief Complaints &amp; Symptoms</h6>
                    <div class="p-3 bg-light rounded text-dark border-start border-4 border-info">
                        {{ $consultation->symptoms }}
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.8rem;">Clinical Diagnosis</h6>
                    <div class="p-3 bg-warning bg-opacity-10 rounded text-dark border-start border-4 border-warning fw-semibold">
                        <i class="bi bi-bookmark-check-fill text-warning me-2"></i>{{ $consultation->diagnosis }}
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.8rem;">Treatment Notes &amp; Management</h6>
                    <div class="p-3 bg-primary bg-opacity-10 rounded text-dark border-start border-4 border-primary">
                        {{ $consultation->treatment_notes }}
                    </div>
                </div>

                <!-- Prescribed Medication Block -->
                <div class="card border border-success border-opacity-25 bg-success bg-opacity-10 mb-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold text-success mb-0">
                                <i class="bi bi-capsule me-1"></i> Prescribed Medication &amp; Dosage
                            </h6>
                            @if($consultation->medicine)
                                <span class="badge bg-success">Dispensed from Pharmacy</span>
                            @else
                                <span class="badge bg-secondary">No Medication Prescribed</span>
                            @endif
                        </div>
                        @if($consultation->medicine)
                            <div class="d-flex justify-content-between align-items-center p-3 bg-white rounded border">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">
                                        <a href="{{ route('medicines.show', $consultation->medicine) }}" class="text-decoration-none text-dark">
                                            {{ $consultation->medicine->name }}
                                        </a>
                                    </h6>
                                    <div class="text-muted small">
                                        <strong>Generic:</strong> {{ $consultation->medicine->generic_name }} &middot;
                                        <strong>Category:</strong> {{ $consultation->medicine->category }} &middot;
                                        <strong>Dosage Form:</strong> <span class="text-capitalize">{{ $consultation->medicine->dosage_form }}</span>
                                    </div>
                                    <div class="mt-2 text-primary fw-medium small">
                                        <i class="bi bi-info-circle me-1"></i><strong>Instructions:</strong> {{ $consultation->dosage_instructions }}
                                    </div>
                                </div>
                                <div class="text-end ps-3">
                                    <div class="fw-bold text-success fs-5">{{ $consultation->prescribed_quantity }}</div>
                                    <small class="text-muted">units dispensed</small>
                                </div>
                            </div>
                        @else
                            <p class="text-muted small mb-0">No pharmaceutical prescription was issued for this consultation.</p>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ route('consultations.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Consultations
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
