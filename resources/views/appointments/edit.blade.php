@extends('layouts.app')

@section('title', 'Edit Appointment')
@section('subtitle', 'Update appointment #' . $appointment->id . ' for ' . $appointment->patient->full_name)

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Update Appointment Details</h5>
                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-eye me-1"></i> View Details
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('appointments.update', $appointment) }}" id="edit-appt-form">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Patient -->
                        <div class="col-12">
                            <label for="appt_patient_id" class="form-label fw-semibold">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="appt_patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }} (DOB: {{ $patient->birthdate->format('M d, Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Health Worker -->
                        <div class="col-12">
                            <label for="appt_hw_id" class="form-label fw-semibold">Attending Health Worker <span class="text-danger">*</span></label>
                            <select name="health_worker_id" id="appt_hw_id" class="form-select @error('health_worker_id') is-invalid @enderror" required>
                                @foreach($healthWorkers as $hw)
                                    <option value="{{ $hw->id }}" {{ old('health_worker_id', $appointment->health_worker_id) == $hw->id ? 'selected' : '' }}>
                                        {{ $hw->full_name }} &mdash; {{ $hw->position }} ({{ $hw->specialization ?? 'General Care' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('health_worker_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div class="col-md-4">
                            <label for="appt_date" class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" id="appt_date"
                                value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                                class="form-control @error('appointment_date') is-invalid @enderror" required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Time Slot -->
                        <div class="col-md-4">
                            <label for="appt_time" class="form-label fw-semibold">Time Slot <span class="text-danger">*</span></label>
                            <select name="appointment_time" id="appt_time" class="form-select @error('appointment_time') is-invalid @enderror" required>
                                @foreach(['08:00'=>'8:00 AM','09:00'=>'9:00 AM','10:00'=>'10:00 AM','11:00'=>'11:00 AM','13:00'=>'1:00 PM','14:00'=>'2:00 PM','15:00'=>'3:00 PM','16:00'=>'4:00 PM'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('appointment_time', substr($appointment->appointment_time, 0, 5)) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('appointment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <label for="appt_status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="appt_status" class="form-select @error('status') is-invalid @enderror" required>
                                @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $s)
                                    <option value="{{ $s }}" {{ old('status', $appointment->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reason -->
                        <div class="col-12">
                            <label for="appt_reason" class="form-label fw-semibold">Reason for Visit <span class="text-danger">*</span></label>
                            <textarea name="reason" id="appt_reason" rows="3" class="form-control @error('reason') is-invalid @enderror"
                                required>{{ old('reason', $appointment->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-chc-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Update Appointment
                        </button>
                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary px-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
