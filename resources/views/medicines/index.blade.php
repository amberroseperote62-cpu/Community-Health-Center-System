@extends('layouts.app')

@section('title', 'Medicine Inventory')
@section('subtitle', 'Pharmacy catalog, real-time stock levels, and expiration monitoring')

@section('content')

<!-- Header Bar: Search & Action Button -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <form method="GET" action="{{ route('medicines.index') }}" class="d-flex align-items-center gap-2">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or category..." id="med-search" class="form-control border-start-0 ps-0" style="max-width: 280px;">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
            </div>
            @if($search)
                <a href="{{ route('medicines.index') }}" class="btn btn-link btn-sm text-decoration-none">Clear</a>
            @endif
        </form>

        <a href="{{ route('medicines.create') }}" id="btn-add-med" class="btn btn-chc-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Add Medicine to Stock</span>
        </a>
    </div>
</div>

<!-- Medicines Table Card -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 60px;">ID</th>
                    <th scope="col">Medicine Name &amp; Generic</th>
                    <th scope="col">Category</th>
                    <th scope="col">Dosage Form</th>
                    <th scope="col">Stock Quantity</th>
                    <th scope="col">Expiry Date</th>
                    <th scope="col" class="text-center" style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $med)
                <tr class="{{ $med->isExpired() ? 'table-danger' : ($med->isNearExpiry() ? 'table-warning' : '') }}">
                    <td class="text-muted fw-bold">#{{ $med->id }}</td>
                    <td>
                        <a href="{{ route('medicines.show', $med) }}" class="fw-semibold text-dark text-decoration-none">
                            {{ $med->name }}
                        </a>
                        <div class="text-muted small"><i class="bi bi-tag me-1"></i>{{ $med->generic_name }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $med->category }}</span>
                    </td>
                    <td>
                        <span class="badge bg-secondary text-capitalize">{{ $med->dosage_form }}</span>
                    </td>
                    <td>
                        <span class="fw-bold fs-6 {{ $med->isLowStock() ? 'text-danger' : 'text-dark' }}">
                            {{ $med->stock_quantity }}
                        </span>
                        <small class="text-muted">/ {{ $med->reorder_level }} reorder</small>
                        @if($med->isLowStock())
                            <span class="badge bg-danger ms-1">Low Stock</span>
                        @endif
                    </td>
                    <td>
                        <div>{{ $med->expiry_date->format('M d, Y') }}</div>
                        @if($med->isExpired())
                            <span class="badge bg-danger">EXPIRED</span>
                        @elseif($med->isNearExpiry())
                            <span class="badge bg-warning text-dark">Expiring Soon</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('medicines.show', $med) }}" class="btn btn-outline-info" title="View Medicine">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('medicines.edit', $med) }}" class="btn btn-outline-primary" title="Edit Medicine">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <button type="button" class="btn btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                                data-delete-url="{{ route('medicines.destroy', $med) }}"
                                data-delete-msg="Are you sure you want to remove {{ $med->name }} from pharmacy inventory?"
                                title="Delete Medicine">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-capsule fs-1 d-block mb-2 text-secondary"></i>
                        No medicines found in the pharmacy catalog{{ $search ? ' matching "' . $search . '"' : '' }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($medicines->hasPages())
    <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-center">
        {{ $medicines->links() }}
    </div>
    @endif
</div>

@endsection
