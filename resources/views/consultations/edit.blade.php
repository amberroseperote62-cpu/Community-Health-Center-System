@extends('layouts.app')

@section('title', 'Edit Consultation')
@section('subtitle', $consultation->patient->full_name)

@section('content')

<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @include('partials.errors')

        <form method="POST" action="{{ route('consultations.update', $consultation) }}" id="edit-consult-form">
            @csrf @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Link to Appointment <span class="text-gray-400 text-xs">(optional)</span></label>
                <select name="appointment_id" id="edit_appt_id"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">No appointment (walk-in)</option>
                    @foreach($appointments as $appt)
                    <option value="{{ $appt->id }}" {{ old('appointment_id', $consultation->appointment_id) == $appt->id ? 'selected' : '' }}>
                        {{ $appt->patient->full_name }} — {{ $appt->appointment_date->format('M d, Y') }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                    <select name="patient_id" id="edit_patient_id"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id', $consultation->patient_id) == $patient->id ? 'selected' : '' }}>
                            {{ $patient->full_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Health Worker <span class="text-red-500">*</span></label>
                    <select name="health_worker_id" id="edit_hw_id"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        @foreach($healthWorkers as $hw)
                        <option value="{{ $hw->id }}" {{ old('health_worker_id', $consultation->health_worker_id) == $hw->id ? 'selected' : '' }}>
                            {{ $hw->full_name }} ({{ $hw->position }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Consultation Date <span class="text-red-500">*</span></label>
                    <input type="date" name="consultation_date" id="edit_consult_date"
                        value="{{ old('consultation_date', $consultation->consultation_date->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Symptoms <span class="text-red-500">*</span></label>
                    <textarea name="symptoms" id="edit_symptoms" rows="2"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('symptoms', $consultation->symptoms) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis <span class="text-red-500">*</span></label>
                    <textarea name="diagnosis" id="edit_diagnosis" rows="2"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('diagnosis', $consultation->diagnosis) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Treatment Notes <span class="text-red-500">*</span></label>
                    <textarea name="treatment_notes" id="edit_treatment" rows="3"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('treatment_notes', $consultation->treatment_notes) }}</textarea>
                </div>
            </div>

            {{-- Prescriptions --}}
            <div class="border-t border-gray-100 pt-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Prescriptions <span class="text-xs text-gray-400 font-normal">(re-entering will restore old stock and deduct new)</span></h3>
                    <button type="button" id="add-medicine-btn"
                        class="flex items-center gap-1.5 text-xs text-teal-600 hover:text-teal-700 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Medicine
                    </button>
                </div>

                <div id="medicines-container" class="space-y-3">
                    {{-- Pre-fill existing prescriptions --}}
                    @foreach($consultation->medicines as $i => $med)
                    <div class="medicine-row grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="col-span-5">
                            <label class="text-xs text-gray-500 mb-1 block">Medicine</label>
                            <select name="medicines[{{ $i }}][medicine_id]"
                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-teal-500">
                                @foreach($medicines as $m)
                                <option value="{{ $m->id }}" {{ $m->id == $med->id ? 'selected' : '' }}>{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="text-xs text-gray-500 mb-1 block">Qty</label>
                            <input type="number" name="medicines[{{ $i }}][quantity]" value="{{ $med->pivot->quantity }}" min="1"
                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-teal-500">
                        </div>
                        <div class="col-span-4">
                            <label class="text-xs text-gray-500 mb-1 block">Dosage Instructions</label>
                            <input type="text" name="medicines[{{ $i }}][dosage_instructions]"
                                value="{{ $med->pivot->dosage_instructions }}"
                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-teal-500">
                        </div>
                        <div class="col-span-1 flex items-end pb-1.5">
                            <button type="button" class="remove-medicine-btn text-red-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <template id="medicine-template">
                    <div class="medicine-row grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="col-span-5">
                            <label class="text-xs text-gray-500 mb-1 block">Medicine</label>
                            <select name="medicines[__INDEX__][medicine_id]"
                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-teal-500">
                                <option value="">Select medicine</option>
                                @foreach($medicines as $med)
                                <option value="{{ $med->id }}">{{ $med->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="text-xs text-gray-500 mb-1 block">Qty</label>
                            <input type="number" name="medicines[__INDEX__][quantity]" min="1" value="1"
                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-teal-500">
                        </div>
                        <div class="col-span-4">
                            <label class="text-xs text-gray-500 mb-1 block">Dosage Instructions</label>
                            <input type="text" name="medicines[__INDEX__][dosage_instructions]"
                                placeholder="e.g. 1 tablet 3x daily"
                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-teal-500">
                        </div>
                        <div class="col-span-1 flex items-end pb-1.5">
                            <button type="button" class="remove-medicine-btn text-red-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">Update Consultation</button>
                <a href="{{ route('consultations.show', $consultation) }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
let medIndex = {{ $consultation->medicines->count() }};
const template  = document.getElementById('medicine-template');
const container = document.getElementById('medicines-container');

// Attach remove listeners to pre-filled rows
container.querySelectorAll('.remove-medicine-btn').forEach(btn => {
    btn.addEventListener('click', function() { this.closest('.medicine-row').remove(); });
});

document.getElementById('add-medicine-btn').addEventListener('click', function() {
    const html = template.innerHTML.replace(/__INDEX__/g, medIndex++);
    const div  = document.createElement('div');
    div.innerHTML = html;
    container.appendChild(div.firstElementChild);
    div.firstElementChild.querySelector('.remove-medicine-btn').addEventListener('click', function() {
        this.closest('.medicine-row').remove();
    });
});
</script>

@endsection
