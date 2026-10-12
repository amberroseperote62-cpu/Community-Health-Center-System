@extends('layouts.app')

@section('title', 'Consultations')
@section('subtitle', 'Medical encounter records, diagnoses, and issued prescriptions')

@section('content')

<!-- Header Bar: Search & Action Button -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <form method="GET" action="{{ route('consultations.index') }}" class="d-flex align-items-center gap-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by patient name..." id="consult-search" class="form-control border-start-0 ps-0" style="max-width: 280px;">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </div>
            @if($search)
                <a href="{{ route('consultations.index') }}" class="btn btn-link btn-sm text-decoration-none">Clear</a>
            @endif
        </form>

        <a href="{{ route('consultations.create') }}" id="btn-add-consult" class="btn btn-chc-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-clipboard2-plus-fill"></i>
            <span>Record New Consultation</span>
        </a>
    </div>
</div>

<!-- Consultations Table Card -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 60px;">ID</th>
                    <th scope="col">Patient</th>
                    <th scope="col">Attending Provider</th>
                    <th scope="col">Consultation Date</th>
                    <th scope="col">Diagnosis</th>
                    <th scope="col">Prescribed Medicine</th>
                    <th scope="col" class="text-center" style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($consultations as $consult)
                <tr>
                    <td class="text-muted fw-bold">#{{ $consult->id }}</td>
                    <td>
                        <a href="{{ route('patients.show', $consult->patient) }}" class="fw-semibold text-dark text-decoration-none">
                            {{ $consult->patient->full_name }}
                        </a>
                    </td>
                    <td>
                        <div class="fw-medium text-dark">{{ $consult->healthWorker->full_name }}</div>
                        <small class="text-muted">{{ $consult->healthWorker->position }}</small>
                    </td>
                    <td>
                        <div class="fw-semibold text-primary"><i class="bi bi-calendar-event me-1"></i>{{ $consult->consultation_date->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <span class="fw-semibold text-dark">{{ Str::limit($consult->diagnosis, 35) }}</span>
                        <div class="text-muted small text-truncate" style="max-width: 260px;">{{ $consult->symptoms }}</div>
                    </td>
                    <td>
                        @if($consult->medicine)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                <i class="bi bi-capsule me-1"></i>{{ $consult->medicine->name }} ({{ $consult->prescribed_quantity }} units)
                            </span>
                        @else
                            <span class="text-muted small">None prescribed</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('consultations.show', $consult) }}" class="btn btn-outline-info" title="View Consultation">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('consultations.edit', $consult) }}" class="btn btn-outline-primary" title="Edit Consultation">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <button type="button" class="btn btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                data-delete-url="{{ route('consultations.destroy', $consult) }}"
                                data-delete-msg="Are you sure you want to delete consultation #{{ $consult->id }} for {{ $consult->patient->full_name }}? Dispensed medicine inventory will be restored."
                                title="Delete Consultation">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-clipboard-x fs-1 d-block mb-2 text-secondary"></i>
                        No consultation records found{{ $search ? ' matching "' . $search . '"' : '' }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($consultations->hasPages())
    <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-center">
        {{ $consultations->links() }}
    </div>
    @endif
</div>

@endsection
