@extends('layouts.app')

@section('title', 'Edit Medicine')
@section('subtitle', 'Update details and stock for ' . $medicine->name)

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Medicine Details (#{{ $medicine->id }})</h5>
                <a href="{{ route('medicines.show', $medicine) }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-eye me-1"></i> View Medicine
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('medicines.update', $medicine) }}" id="edit-med-form">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Brand Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Brand / Commercial Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $medicine->name) }}"
                                class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Generic Name -->
                        <div class="col-md-6">
                            <label for="generic_name" class="form-label fw-semibold">Generic Name <span class="text-danger">*</span></label>
                            <input type="text" name="generic_name" id="generic_name" value="{{ old('generic_name', $medicine->generic_name) }}"
                                class="form-control @error('generic_name') is-invalid @enderror" required>
                            @error('generic_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="col-md-6">
                            <label for="category" class="form-label fw-semibold">Therapeutic Category <span class="text-danger">*</span></label>
                            <input type="text" name="category" id="category" value="{{ old('category', $medicine->category) }}"
                                class="form-control @error('category') is-invalid @enderror" required>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dosage Form -->
                        <div class="col-md-6">
                            <label for="dosage_form" class="form-label fw-semibold">Dosage Form <span class="text-danger">*</span></label>
                            <select name="dosage_form" id="dosage_form" class="form-select @error('dosage_form') is-invalid @enderror" required>
                                @foreach(['tablet', 'syrup', 'capsule', 'injection', 'ointment', 'drops', 'other'] as $form)
                                    <option value="{{ $form }}" {{ old('dosage_form', $medicine->dosage_form) === $form ? 'selected' : '' }}>{{ ucfirst($form) }}</option>
                                @endforeach
                            </select>
                            @error('dosage_form')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Quantity -->
                        <div class="col-md-6">
                            <label for="stock_quantity" class="form-label fw-semibold">Current Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $medicine->stock_quantity) }}" min="0"
                                class="form-control @error('stock_quantity') is-invalid @enderror" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reorder Level -->
                        <div class="col-md-6">
                            <label for="reorder_level" class="form-label fw-semibold">Reorder Warning Level <span class="text-danger">*</span></label>
                            <input type="number" name="reorder_level" id="reorder_level" value="{{ old('reorder_level', $medicine->reorder_level) }}" min="0"
                                class="form-control @error('reorder_level') is-invalid @enderror" required>
                            @error('reorder_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Expiry Date -->
                        <div class="col-12">
                            <label for="expiry_date" class="form-label fw-semibold">Batch Expiration Date <span class="text-danger">*</span></label>
                            <input type="date" name="expiry_date" id="expiry_date"
                                value="{{ old('expiry_date', $medicine->expiry_date->format('Y-m-d')) }}"
                                class="form-control @error('expiry_date') is-invalid @enderror" required>
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-chc-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Update Medicine
                        </button>
                        <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary px-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
