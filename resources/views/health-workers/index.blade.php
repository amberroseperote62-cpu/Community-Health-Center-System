@extends('layouts.app')

@section('title', 'Health Workers')
@section('subtitle', 'Manage clinical personnel, doctors, nurses, and staff directory')

@section('content')

<!-- Header Bar: Search & Action Button -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <form method="GET" action="{{ route('health-workers.index') }}" class="d-flex align-items-center gap-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or position..." id="hw-search" class="form-control border-start-0 ps-0" style="max-width: 280px;">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </div>
            @if($search)
                <a href="{{ route('health-workers.index') }}" class="btn btn-link btn-sm text-decoration-none">Clear</a>
            @endif
        </form>

        <a href="{{ route('health-workers.create') }}" id="btn-add-hw" class="btn btn-chc-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i>
            <span>Add Health Worker</span>
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 60px;">ID</th>
                    <th scope="col">Name &amp; Email</th>
                    <th scope="col">Position</th>
                    <th scope="col">Specialization</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-center" style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($healthWorkers as $hw)
                <tr>
                    <td class="text-muted fw-bold">#{{ $hw->id }}</td>
                    <td>
                        <a href="{{ route('health-workers.show', $hw) }}" class="fw-semibold text-dark text-decoration-none">
                            {{ $hw->full_name }}
                        </a>
                        <div class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $hw->email }}</div>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ $hw->position }}
                        </span>
                    </td>
                    <td class="text-muted">{{ $hw->specialization ?? 'General Care' }}</td>
                    <td><i class="bi bi-telephone me-1 text-muted"></i>{{ $hw->contact_number }}</td>
                    <td>
                        <span class="badge {{ $hw->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($hw->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('health-workers.show', $hw) }}" class="btn btn-outline-info" title="View Profile">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('health-workers.edit', $hw) }}" class="btn btn-outline-primary" title="Edit Staff">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <button type="button" class="btn btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                data-delete-url="{{ route('health-workers.destroy', $hw) }}"
                                data-delete-msg="Are you sure you want to delete {{ $hw->full_name }}? Their related appointment records will also be removed."
                                title="Delete Staff">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                        No health workers found{{ $search ? ' matching "' . $search . '"' : '' }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($healthWorkers->hasPages())
    <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-center">
        {{ $healthWorkers->links() }}
    </div>
    @endif
</div>

@endsection
