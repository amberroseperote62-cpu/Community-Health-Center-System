@extends('layouts.app')

@section('title', 'Patients')
@section('subtitle', 'Manage registered patient records and medical histories')

@section('content')

<!-- Header Bar: Search & Action Button -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <form method="GET" action="{{ route('patients.index') }}" class="d-flex align-items-center gap-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name..." id="patient-search" class="form-control border-start-0 ps-0" style="max-width: 280px;">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </div>
            @if($search)
                <a href="{{ route('patients.index') }}" class="btn btn-link btn-sm text-decoration-none">Clear</a>
            @endif
        </form>

        <a href="{{ route('patients.create') }}" id="btn-add-patient" class="btn btn-chc-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i>
            <span>Register New Patient</span>
        </a>
    </div>
</div>

<!-- Patients Table Card -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 60px;">ID</th>
                    <th scope="col">Full Name &amp; Address</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Birthdate / Age</th>
                    <th scope="col">Contact</th>
                    <th scope="col" class="text-center" style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td class="text-muted fw-bold">#{{ $patient->id }}</td>
                    <td>
                        <a href="{{ route('patients.show', $patient) }}" class="fw-semibold text-dark text-decoration-none">
                            {{ $patient->full_name }}
                        </a>
                        <div class="text-muted small text-truncate" style="max-width: 320px;">
                            <i class="bi bi-geo-alt me-1"></i>{{ $patient->address }}
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $patient->gender === 'Male' ? 'bg-primary' : ($patient->gender === 'Female' ? 'bg-danger' : 'bg-secondary') }}">
                            {{ $patient->gender }}
                        </span>
                    </td>
                    <td>
                        <div>{{ $patient->birthdate->format('M d, Y') }}</div>
                        <small class="text-muted">({{ $patient->age }} years old)</small>
                    </td>
                    <td>
                        <div><i class="bi bi-telephone me-1 text-muted"></i>{{ $patient->contact_number }}</div>
                        @if($patient->emergency_contact)
                            <small class="text-muted"><i class="bi bi-person-heart me-1"></i>{{ $patient->emergency_contact }}</small>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-info" title="View Profile">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline-primary" title="Edit Patient">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <button type="button" class="btn btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                data-delete-url="{{ route('patients.destroy', $patient) }}"
                                data-delete-msg="Are you sure you want to delete patient record for {{ $patient->full_name }}? This will soft-delete the patient."
                                title="Delete Patient">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                        No patient records found{{ $search ? ' matching "' . $search . '"' : '' }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($patients->hasPages())
    <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-center">
        {{ $patients->links() }}
    </div>
    @endif
</div>

@endsection
