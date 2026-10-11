@extends('layouts.app')

@section('title', 'Record Consultation')
@section('subtitle', 'Document a medical visit')

@section('content')

<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @include('partials.errors')

        <form method="POST" action="{{ route('consultations.store') }}" id="create-consult-form">
            @csrf

            {{-- Link to Appointment (optional) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Link to Appointment <span class="text-gray-400 text-xs">(optional)</span></label>
                <select name="appointment_id" id="consult_appointment_id"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">No appointment (walk-in)</option>
                    @foreach($appointments as $appt)
                    <option value="{{ $appt->id }}" {{ old('appointment_id') == $appt->id ? 'selected' : '' }}>
                        {{ $appt->patient->full_name }} — {{ $appt->appointment_date->format('M d, Y') }} ({{ $appt->reason }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                    <select name="patient_id" id="consult_patient_id"
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

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Health Worker <span class="text-red-500">*</span></label>
                    <select name="health_worker_id" id="consult_hw_id"
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

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Consultation Date <span class="text-red-500">*</span></label>
                    <input type="date" name="consultation_date" id="consult_date"
                        value="{{ old('consultation_date', today()->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('consultation_date') border-red-400 @enderror">
                    @error('consultation_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Symptoms <span class="text-red-500">*</span></label>
                    <textarea name="symptoms" id="consult_symptoms" rows="2"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('symptoms') border-red-400 @enderror"
                        placeholder="Patient-reported symptoms...">{{ old('symptoms') }}</textarea>
                    @error('symptoms')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis <span class="text-red-500">*</span></label>
                    <textarea name="diagnosis" id="consult_diagnosis" rows="2"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('diagnosis') border-red-400 @enderror"
                        placeholder="Clinical diagnosis...">{{ old('diagnosis') }}</textarea>
                    @error('diagnosis')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Treatment Notes <span class="text-red-500">*</span></label>
                    <textarea name="treatment_notes" id="consult_treatment" rows="3"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('treatment_notes') border-red-400 @enderror"
                        placeholder="Treatment plan, medications, instructions...">{{ old('treatment_notes') }}</textarea>
                    @error('treatment_notes')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- ===== Prescriptions Section ===== --}}
            <div class="border-t border-gray-100 pt-5 mt-2">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Prescriptions</h3>
                    <button type="button" id="add-medicine-btn"
                        class="flex items-center gap-1.5 text-xs text-teal-600 hover:text-teal-700 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Medicine
                    </button>
                </div>

                <div id="medicines-container" class="space-y-3">
                    {{-- Medicine rows are added dynamically by JS --}}
                </div>

                {{-- Medicine row template (hidden) --}}
                <template id="medicine-template">
                    <div class="medicine-row grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="col-span-5">
                            <label class="text-xs text-gray-500 mb-1 block">Medicine</label>
                            <select name="medicines[__INDEX__][medicine_id]"
                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-teal-500">
                                <option value="">Select medicine</option>
                                @foreach($medicines as $med)
                                <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->stock_quantity }} in stock)</option>
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
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Save Consultation
                </button>
                <a href="{{ route('consultations.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
let medIndex = 0;
const template  = document.getElementById('medicine-template');
const container = document.getElementById('medicines-container');

document.getElementById('add-medicine-btn').addEventListener('click', function() {
    // Clone the template and replace __INDEX__ with current index
    const html = template.innerHTML.replace(/__INDEX__/g, medIndex++);
    const div  = document.createElement('div');
    div.innerHTML = html;
    container.appendChild(div.firstElementChild);

    // Attach remove button listener
    div.firstElementChild.querySelector('.remove-medicine-btn').addEventListener('click', function() {
        this.closest('.medicine-row').remove();
    });
});
</script>

@endsection
