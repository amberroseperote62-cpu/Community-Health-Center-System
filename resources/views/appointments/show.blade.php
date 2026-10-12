@extends('layouts.app')

@section('title', 'Appointment Details')
@section('subtitle', 'Appointment #' . $appointment->id . ' for ' . $appointment->patient->full_name)

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar2-event text-primary fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Appointment Information</h5>
                </div>
                <div>
                    @php
                        $statusBadges = [
                            'pending' => 'bg-warning text-dark',
                            'confirmed' => 'bg-primary',
                            'completed' => 'bg-success',
                            'cancelled' => 'bg-secondary',
                        ];
                    @endphp
                    <span class="badge {{ $statusBadges[$appointment->status] ?? 'bg-secondary' }} fs-6 text-capitalize">
                        {{ $appointment->status }}
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Patient</small>
                        <a href="{{ route('patients.show', $appointment->patient) }}" class="fw-bold text-dark text-decoration-none fs-6">
                            {{ $appointment->patient->full_name }}
                        </a>
                        <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ $appointment->patient->contact_number }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Assigned Health Worker</small>
                        <a href="{{ route('health-workers.show', $appointment->healthWorker) }}" class="fw-bold text-dark text-decoration-none fs-6">
                            {{ $appointment->healthWorker->full_name }}
                        </a>
                        <div class="small text-muted">{{ $appointment->healthWorker->position }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Scheduled Date</small>
                        <div class="fw-semibold text-primary fs-6"><i class="bi bi-calendar3 me-1"></i>{{ $appointment->appointment_date->format('F d, Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Time Slot</small>
                        <div class="fw-semibold fs-6"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.75rem;">Reason for Visit</small>
                        <div class="p-3 bg-light rounded text-dark">{{ $appointment->reason }}</div>
                    </div>
                </div>

                <!-- Linked Consultation Section -->
                @if($appointment->consultation)
                <div class="p-3 rounded border border-success border-opacity-25 bg-success bg-opacity-10 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="fw-bold text-success mb-0"><i class="bi bi-clipboard2-check me-1"></i>Linked Clinical Consultation</h6>
                        <span class="small text-muted">{{ $appointment->consultation->consultation_date->format('M d, Y') }}</span>
                    </div>
                    <p class="mb-2 small text-dark"><strong>Diagnosis:</strong> {{ $appointment->consultation->diagnosis }}</p>
                    <a href="{{ route('consultations.show', $appointment->consultation) }}" class="btn btn-sm btn-success">
                        View Full Consultation Record &rarr;
                    </a>
                </div>
                @else
                <div class="alert alert-info d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <i class="bi bi-info-circle me-1"></i> No clinical consultation recorded yet for this appointment.
                    </div>
                    <a href="{{ route('consultations.create', ['appointment_id' => $appointment->id, 'patient_id' => $appointment->patient_id, 'health_worker_id' => $appointment->health_worker_id]) }}" class="btn btn-sm btn-primary">
                        Record Consultation
                    </a>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Appointments
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        @if(auth()->user()->isAdmin())
                        <button type="button" class="btn btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                            data-delete-url="{{ route('appointments.destroy', $appointment) }}"
                            data-delete-msg="Are you sure you want to delete appointment #{{ $appointment->id }}?">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
