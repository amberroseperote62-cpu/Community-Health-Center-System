@extends('layouts.app')

@section('title', 'Edit Patient')
@section('subtitle', 'Update clinical information for ' . $patient->full_name)

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Patient Record (#{{ $patient->id }})</h5>
                <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-eye me-1"></i> View Profile
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('patients.update', $patient) }}" id="edit-patient-form">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- First Name -->
                        <div class="col-md-6">
                            <label for="first_name" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $patient->first_name) }}"
                                class="form-control @error('first_name') is-invalid @enderror" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-6">
                            <label for="last_name" class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $patient->last_name) }}"
                                class="form-control @error('last_name') is-invalid @enderror" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Birthdate -->
                        <div class="col-md-6">
                            <label for="birthdate" class="form-label fw-semibold">Birthdate <span class="text-danger">*</span></label>
                            <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate', $patient->birthdate->format('Y-m-d')) }}"
                                class="form-control @error('birthdate') is-invalid @enderror" max="{{ date('Y-m-d') }}" required>
                            @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6">
                            <label for="gender" class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                @foreach(['Male', 'Female', 'Other'] as $g)
                                    <option value="{{ $g }}" {{ old('gender', $patient->gender) === $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="col-md-6">
                            <label for="contact_number" class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', $patient->contact_number) }}"
                                class="form-control @error('contact_number') is-invalid @enderror" required>
                            @error('contact_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Emergency Contact -->
                        <div class="col-md-6">
                            <label for="emergency_contact" class="form-label fw-semibold">Emergency Contact</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact', $patient->emergency_contact) }}"
                                class="form-control @error('emergency_contact') is-invalid @enderror">
                            @error('emergency_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold">Complete Address <span class="text-danger">*</span></label>
                            <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                                required>{{ old('address', $patient->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-chc-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Update Patient Record
                        </button>
                        <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary px-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
