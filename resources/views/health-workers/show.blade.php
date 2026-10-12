@extends('layouts.app')

@section('title', $healthWorker->full_name)
@section('subtitle', $healthWorker->position . ' Profile & Activity')

@section('content')

<div class="row g-4">
    <!-- Health Worker Profile Card -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center border-bottom">
                <div class="rounded-circle bg-success bg-opacity-10 text-success fw-bold mx-auto mb-3 d-flex align-items-center justify-content-center fs-2" style="width: 80px; height: 80px;">
                    {{ strtoupper(substr($healthWorker->first_name, 0, 1)) }}
                </div>
                <h4 class="fw-bold mb-1 text-dark">{{ $healthWorker->full_name }}</h4>
                <div class="mb-2">
                    <span class="badge bg-info text-dark">{{ $healthWorker->position }}</span>
                    <span class="badge {{ $healthWorker->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($healthWorker->status) }}
                    </span>
                </div>
                <small class="text-muted"><i class="bi bi-person-badge me-1"></i>Staff ID: #{{ $healthWorker->id }}</small>
            </div>
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Professional Details</h6>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Specialization</span>
                        <span class="fw-semibold">{{ $healthWorker->specialization ?? 'General Healthcare' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Contact</span>
                        <span class="fw-semibold">{{ $healthWorker->contact_number }}</span>
                    </li>
                    <li class="py-2 border-bottom">
                        <div class="text-muted small mb-1">Official Email</div>
                        <div class="fw-semibold text-break">{{ $healthWorker->email }}</div>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Status</span>
                        <span class="fw-semibold text-capitalize">{{ $healthWorker->status }}</span>
                    </li>
                </ul>

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('health-workers.edit', $healthWorker) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Staff Profile
                    </a>
                    @if(auth()->user()->isAdmin())
                    <button type="button" class="btn btn-outline-danger"
                        data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                        data-delete-url="{{ route('health-workers.destroy', $healthWorker) }}"
                        data-delete-msg="Are you sure you want to delete {{ $healthWorker->full_name }}?">
                        <i class="bi bi-trash me-1"></i> Delete Staff Record
                    </button>
                    @endif
                    <a href="{{ route('health-workers.index') }}" class="btn btn-light border text-muted">
                        <i class="bi bi-arrow-left me-1"></i> Back to Directory
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Appointments and Conducted Consultations -->
    <div class="col-12 col-lg-8">
        <!-- Assigned Appointments -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-event text-primary fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Assigned Appointments</h5>
                </div>
                <span class="badge bg-secondary rounded-pill">{{ $healthWorker->appointments->count() }} records</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 280px; overflow-y: auto;">
                    @forelse($healthWorker->appointments as $appt)
                    <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark">{{ $appt->patient->full_name }}</div>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>{{ $appt->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}
                                &middot; {{ $appt->reason }}
                            </small>
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
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-x fs-2 d-block mb-1 text-secondary"></i>
                        No appointments currently scheduled for this worker.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Consultations Conducted -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard2-pulse text-success fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Recent Clinical Consultations</h5>
                </div>
                <span class="badge bg-success rounded-pill">{{ $healthWorker->consultations->count() }} visits</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 280px; overflow-y: auto;">
                    @forelse($healthWorker->consultations as $c)
                    <div class="list-group-item p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-dark">{{ $c->patient->full_name }}</span>
                            <small class="text-muted">{{ $c->consultation_date->format('M d, Y') }}</small>
                        </div>
                        <div class="small text-muted mb-1">
                            <strong>Diagnosis:</strong> <span class="text-primary">{{ $c->diagnosis }}</span>
                        </div>
                        @if($c->medicine)
                        <div class="small text-success">
                            <i class="bi bi-capsule me-1"></i>Prescribed: {{ $c->medicine->name }} ({{ $c->prescribed_quantity }} units)
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-clipboard-x fs-2 d-block mb-1 text-secondary"></i>
                        No consultations conducted yet.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
