@extends('layouts.app')

@section('title', 'New Appointment')
@section('subtitle', 'Schedule a patient appointment')

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @include('partials.errors')

        <form method="POST" action="{{ route('appointments.store') }}" id="create-appt-form">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                {{-- Patient --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                    <select name="patient_id" id="appt_patient_id"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('patient_id') border-red-400 @enderror">
                        <option value="">Select patient</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->full_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('patient_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Health Worker --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Health Worker <span class="text-red-500">*</span></label>
                    <select name="health_worker_id" id="appt_hw_id"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('health_worker_id') border-red-400 @enderror">
                        <option value="">Select health worker</option>
                        @foreach($healthWorkers as $hw)
                        <option value="{{ $hw->id }}" {{ old('health_worker_id') == $hw->id ? 'selected' : '' }}>
                            {{ $hw->full_name }} ({{ $hw->position }})
                        </option>
                        @endforeach
                    </select>
                    @error('health_worker_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date <span class="text-red-500">*</span></label>
                    <input type="date" name="appointment_date" id="appt_date" value="{{ old('appointment_date') }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('appointment_date') border-red-400 @enderror">
                    @error('appointment_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Time --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time <span class="text-red-500">*</span></label>
                    <select name="appointment_time" id="appt_time"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('appointment_time') border-red-400 @enderror">
                        <option value="">Select time</option>
                        @foreach(['08:00'=>'8:00 AM','09:00'=>'9:00 AM','10:00'=>'10:00 AM','11:00'=>'11:00 AM','13:00'=>'1:00 PM','14:00'=>'2:00 PM','15:00'=>'3:00 PM','16:00'=>'4:00 PM'] as $val => $label)
                        <option value="{{ $val }}" {{ old('appointment_time') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('appointment_time')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="appt_status"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', 'pending') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Reason --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Visit <span class="text-red-500">*</span></label>
                    <textarea name="reason" id="appt_reason" rows="3"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('reason') border-red-400 @enderror"
                        placeholder="Describe the reason for the appointment...">{{ old('reason') }}</textarea>
                    @error('reason')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Schedule Appointment
                </button>
                <a href="{{ route('appointments.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
