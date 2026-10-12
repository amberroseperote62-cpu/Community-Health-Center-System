@extends('layouts.app')

@section('title', 'Add Health Worker')
@section('subtitle', 'Register a new clinical personnel profile')

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-person-plus text-primary me-2"></i>Health Worker Information</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('health-workers.store') }}" id="create-hw-form">
                    @csrf

                    <div class="row g-3">
                        <!-- First Name -->
                        <div class="col-md-6">
                            <label for="first_name" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                class="form-control @error('first_name') is-invalid @enderror" placeholder="Maria" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-6">
                            <label for="last_name" class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                class="form-control @error('last_name') is-invalid @enderror" placeholder="Santos" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Position -->
                        <div class="col-md-6">
                            <label for="position" class="form-label fw-semibold">Position <span class="text-danger">*</span></label>
                            <select name="position" id="position" class="form-select @error('position') is-invalid @enderror" required>
                                <option value="">Select position...</option>
                                @foreach(['Doctor', 'Nurse', 'Midwife', 'Barangay Health Worker'] as $pos)
                                    <option value="{{ $pos }}" {{ old('position') === $pos ? 'selected' : '' }}>{{ $pos }}</option>
                                @endforeach
                            </select>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Specialization -->
                        <div class="col-md-6">
                            <label for="specialization" class="form-label fw-semibold">Medical Specialization</label>
                            <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}"
                                class="form-control @error('specialization') is-invalid @enderror" placeholder="e.g. Family Medicine, Pediatrics">
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Official Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" placeholder="maria.santos@chc.local" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="col-md-6">
                            <label for="contact_number" class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}"
                                class="form-control @error('contact_number') is-invalid @enderror" placeholder="09187654321" required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Duty Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">Duty Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-chc-primary px-4">
                            <i class="bi bi-save me-1"></i> Register Health Worker
                        </button>
                        <a href="{{ route('health-workers.index') }}" class="btn btn-outline-secondary px-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
