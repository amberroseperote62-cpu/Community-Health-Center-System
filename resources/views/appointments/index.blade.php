@extends('layouts.app')

@section('title', 'Appointments')
@section('subtitle', 'Schedule patient visits, prevent conflict bookings, and manage statuses')

@section('content')

<!-- Filter Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('appointments.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search patient name..." id="appt-search" class="form-control">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" id="appt-status-filter" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <input type="date" name="date" value="{{ $date }}" id="appt-date-filter" class="form-control">
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
                @if($search || $status || $date)
                    <a href="{{ route('appointments.index') }}" class="btn btn-link btn-sm text-decoration-none">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Header Action -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-calendar2-check text-primary me-2"></i>Scheduled Appointments List</h5>
    <a href="{{ route('appointments.create') }}" id="btn-add-appt" class="btn btn-chc-primary d-inline-flex align-items-center gap-2">
        <i class="bi bi-calendar-plus-fill"></i>
        <span>Book New Appointment</span>
    </a>
</div>

<!-- Appointments Table Card -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 60px;">ID</th>
                    <th scope="col">Patient</th>
                    <th scope="col">Assigned Worker</th>
                    <th scope="col">Schedule (Date &amp; Time)</th>
                    <th scope="col">Reason / Chief Complaint</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-center" style="width: 220px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appt)
                <tr>
                    <td class="text-muted fw-bold">#{{ $appt->id }}</td>
                    <td>
                        <a href="{{ route('patients.show', $appt->patient) }}" class="fw-semibold text-dark text-decoration-none">
                            {{ $appt->patient->full_name }}
                        </a>
                        <div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $appt->patient->contact_number }}</div>
                    </td>
                    <td>
                        <span class="fw-medium text-dark">{{ $appt->healthWorker->full_name }}</span>
                        <div class="text-muted small">{{ $appt->healthWorker->position }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-primary"><i class="bi bi-calendar3 me-1"></i>{{ $appt->appointment_date->format('M d, Y') }}</div>
                        <div class="text-muted small"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</div>
                    </td>
                    <td class="text-muted small">{{ Str::limit($appt->reason, 35) }}</td>
                    <td>
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
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('appointments.show', $appt) }}" class="btn btn-outline-info" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('appointments.edit', $appt) }}" class="btn btn-outline-primary" title="Edit Appointment">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <button type="button" class="btn btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                data-delete-url="{{ route('appointments.destroy', $appt) }}"
                                data-delete-msg="Are you sure you want to delete appointment #{{ $appt->id }} for {{ $appt->patient->full_name }}?"
                                title="Delete Appointment">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                        @if($appt->status === 'confirmed' && !$appt->consultation)
                        <a href="{{ route('consultations.create', ['appointment_id' => $appt->id, 'patient_id' => $appt->patient_id, 'health_worker_id' => $appt->health_worker_id]) }}" class="btn btn-sm btn-success ms-1 py-0 px-2" style="font-size: 0.75rem;" title="Start Clinical Consultation">
                            <i class="bi bi-clipboard2-pulse"></i> Consult
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                        No appointments found matching your filter criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($appointments->hasPages())
    <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-center">
        {{ $appointments->links() }}
    </div>
    @endif
</div>

@endsection
