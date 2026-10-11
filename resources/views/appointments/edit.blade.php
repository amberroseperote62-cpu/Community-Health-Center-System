@extends('layouts.app')

@section('title', 'Edit Appointment')
@section('subtitle', $appointment->patient->full_name)

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @include('partials.errors')

        <form method="POST" action="{{ route('appointments.update', $appointment) }}" id="edit-appt-form">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                    <select name="patient_id" id="appt_patient_id"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                            {{ $patient->full_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Health Worker <span class="text-red-500">*</span></label>
                    <select name="health_worker_id" id="appt_hw_id"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        @foreach($healthWorkers as $hw)
                        <option value="{{ $hw->id }}" {{ old('health_worker_id', $appointment->health_worker_id) == $hw->id ? 'selected' : '' }}>
                            {{ $hw->full_name }} ({{ $hw->position }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date <span class="text-red-500">*</span></label>
                    <input type="date" name="appointment_date" id="appt_date"
                        value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('appointment_date') border-red-400 @enderror">
                    @error('appointment_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time <span class="text-red-500">*</span></label>
                    <select name="appointment_time" id="appt_time"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('appointment_time') border-red-400 @enderror">
                        @foreach(['08:00'=>'8:00 AM','09:00'=>'9:00 AM','10:00'=>'10:00 AM','11:00'=>'11:00 AM','13:00'=>'1:00 PM','14:00'=>'2:00 PM','15:00'=>'3:00 PM','16:00'=>'4:00 PM'] as $val => $label)
                        <option value="{{ $val }}" {{ old('appointment_time', substr($appointment->appointment_time, 0, 5)) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('appointment_time')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="appt_status"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $appointment->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason <span class="text-red-500">*</span></label>
                    <textarea name="reason" id="appt_reason" rows="3"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('reason', $appointment->reason) }}</textarea>
                    @error('reason')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">Update Appointment</button>
                <a href="{{ route('appointments.show', $appointment) }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
