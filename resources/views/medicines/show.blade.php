@extends('layouts.app')

@section('title', $medicine->name)
@section('subtitle', $medicine->category . ' &middot; ' . ucfirst($medicine->dosage_form))

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-capsule text-primary fs-5"></i>
                    <h5 class="card-title mb-0 fw-bold">Medicine Profile &amp; Inventory Details</h5>
                </div>
                <div>
                    @if($medIsExpired = $medicine->isExpired())
                        <span class="badge bg-danger fs-6">EXPIRED</span>
                    @elseif($medicine->isNearExpiry())
                        <span class="badge bg-warning text-dark fs-6">Expiring Soon</span>
                    @else
                        <span class="badge bg-success fs-6">Active Stock</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Status Alerts -->
                @if($medicine->isExpired())
                <div class="alert alert-danger d-flex align-items-center mb-3">
                    <i class="bi bi-exclamation-octagon-fill fs-5 me-2"></i>
                    <div>This pharmaceutical product is <strong>expired</strong> and must not be dispensed.</div>
                </div>
                @elseif($medicine->isNearExpiry())
                <div class="alert alert-warning d-flex align-items-center mb-3">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <div>This medicine expires soon &mdash; <strong>{{ $medicine->expiry_date->diffForHumans() }}</strong>.</div>
                </div>
                @endif

                @if($medicine->isLowStock())
                <div class="alert alert-danger d-flex align-items-center mb-3">
                    <i class="bi bi-graph-down-arrow fs-5 me-2"></i>
                    <div>Low stock alert: only <strong>{{ $medicine->stock_quantity }} units</strong> remain (reorder threshold: {{ $medicine->reorder_level }}).</div>
                </div>
                @endif

                <ul class="list-unstyled mb-4">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Brand / Commercial Name</span>
                        <span class="fw-bold fs-6 text-dark">{{ $medicine->name }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Generic Formulation</span>
                        <span class="fw-semibold">{{ $medicine->generic_name }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Therapeutic Category</span>
                        <span class="fw-semibold">{{ $medicine->category }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Dosage Form</span>
                        <span class="badge bg-secondary text-capitalize">{{ $medicine->dosage_form }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Current Stock Level</span>
                        <span class="fw-bold fs-5 {{ $medicine->isLowStock() ? 'text-danger' : 'text-dark' }}">{{ $medicine->stock_quantity }} units</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Reorder Threshold</span>
                        <span class="fw-semibold">{{ $medicine->reorder_level }} units</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Batch Expiration Date</span>
                        <span class="fw-bold {{ $medicine->isExpired() ? 'text-danger' : 'text-dark' }}">
                            {{ $medicine->expiry_date->format('F d, Y') }}
                        </span>
                    </li>
                </ul>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Inventory
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i> Edit Stock
                        </a>
                        @if(auth()->user()->isAdmin())
                        <button type="button" class="btn btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                            data-delete-url="{{ route('medicines.destroy', $medicine) }}"
                            data-delete-msg="Are you sure you want to delete {{ $medicine->name }}?">
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
