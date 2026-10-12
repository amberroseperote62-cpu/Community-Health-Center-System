@extends('layouts.app')

@section('title', $patient->full_name)
@section('subtitle', 'Patient Clinical Profile & Record History')

@section('content')

<div class="row g-4">
    <!-- Patient Profile Summary Card -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center border-bottom">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold mx-auto mb-3 d-flex align-items-center justify-content-center fs-2" style="width: 80px; height: 80px;">
                    {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                </div>
                <h4 class="fw-bold mb-1 text-dark">{{ $patient->full_name }}</h4>
                <div class="text-muted small mb-2"><i class="bi bi-person-vcard me-1"></i>Patient ID: #{{ $patient->id }}</div>
                <span class="badge {{ $patient->gender === 'Male' ? 'bg-primary' : ($patient->gender === 'Female' ? 'bg-danger' : 'bg-secondary') }}">
                    {{ $patient->gender }}
                </span>
            </div>
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Personal &amp; Contact Details</h6>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Birthdate</span>
                        <span class="fw-semibold">{{ $patient->birthdate->format('F d, Y') }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Age</span>
                        <span class="fw-semibold">{{ $patient->birthdate->age }} years old</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Contact</span>
                        <span class="fw-semibold">{{ $patient->contact_number }}</span>
                    </li>
                    <li class="py-2 border-bottom">
                        <div class="text-muted small mb-1">Residential Address</div>
                        <div class="fw-semibold">{{ $patient->address }}</div>
                    </li>
                    @if($patient->emergency_contact)
                    <li class="py-2">
                        <div class="text-muted small mb-1">Emergency Contact</div>
                        <div class="fw-semibold text-danger"><i class="bi bi-telephone-inbound me-1"></i>{{ $patient->emergency_contact }}</div>
                    </li>
                    @endif
                </ul>

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Patient Details
                    </a>
                    @if(auth()->user()->isAdmin())
                    <button type="button" class="btn btn-outline-danger"
                        data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                        data-delete-url="{{ route('patients.destroy', $patient) }}"
                        data-delete-msg="Are you sure you want to delete patient record for {{ $patient->full_name }}?">
                        <i class="bi bi-trash me-1"></i> Delete Patient Record
                    </button>
                    @endif
                    <a href="{{ route('patients.index') }}" class="btn btn-light border text-muted">
                        <i class="bi bi-arrow-left me-1"></i> Back to Patient Registry
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Clinical History Columns -->
    <div class="col-12 col-lg-8">
        <!-- Appointment History Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-check text-primary fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Appointment History</h5>
                </div>
                <span class="badge bg-secondary rounded-pill">{{ $patient->appointments->count() }} records</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 280px; overflow-y: auto;">
                    @forelse($patient->appointments as $appt)
                    <div class="list-group-item p-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <span class="fw-bold text-dark me-2">
                                    {{ $appt->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}
                                </span>
                                <span class="badge bg-light text-dark border">Dr./Worker: {{ $appt->healthWorker->full_name }}</span>
                            </div>
                            @php
                                $statusBadges = [
                                    'pending' => 'bg-warning text-dark',
                                    'confirmed' => 'bg-primary',
                                    'completed' => 'bg-success',
                                    'cancelled' => 'bg-secondary',
                                ];
                            @endphp
                            <span class="badge {{ $statusBadges[$appt->status] ?? 'bg-secondary' }} text-capitalize">
                                {{ $appt->status }}
                            </span>
                        </div>
                        <p class="text-muted small mb-0"><i class="bi bi-chat-left-dots me-1"></i><strong>Reason:</strong> {{ $appt->reason }}</p>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-x fs-2 d-block mb-1 text-secondary"></i>
                        No appointments recorded for this patient.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Consultation & Clinical Encounter History Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard2-pulse text-success fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Consultation History &amp; Prescriptions</h5>
                </div>
                <span class="badge bg-success rounded-pill">{{ $patient->consultations->count() }} visits</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 380px; overflow-y: auto;">
                    @forelse($patient->consultations as $consult)
                    <div class="list-group-item p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-calendar-event me-1 text-primary"></i>
                                {{ $consult->consultation_date->format('F d, Y') }}
                            </h6>
                            <span class="badge bg-light text-dark border">
                                Attending: {{ $consult->healthWorker->full_name }}
                            </span>
                        </div>
                        <div class="row g-2 small text-muted mb-2">
                            <div class="col-12">
                                <strong class="text-dark">Symptoms:</strong> {{ $consult->symptoms }}
                            </div>
                            <div class="col-12">
                                <strong class="text-dark">Diagnosis:</strong>
                                <span class="text-primary fw-semibold">{{ $consult->diagnosis }}</span>
                            </div>
                            <div class="col-12">
                                <strong class="text-dark">Treatment Notes:</strong> {{ $consult->treatment_notes }}
                            </div>
                        </div>

                        @if($consult->medicine)
                        <div class="p-2 bg-light rounded border mb-2 small">
                            <div class="fw-semibold text-success mb-1">
                                <i class="bi bi-capsule me-1"></i> Prescribed Medication:
                            </div>
                            <div>
                                <strong>{{ $consult->medicine->name }}</strong> ({{ $consult->prescribed_quantity }} units)
                                &mdash; <span class="text-muted">{{ $consult->dosage_instructions }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="text-end">
                            <a href="{{ route('consultations.show', $consult) }}" class="btn btn-sm btn-outline-info">
                                View Full Consultation Record <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-clipboard-x fs-2 d-block mb-1 text-secondary"></i>
                        No medical consultations logged for this patient yet.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
