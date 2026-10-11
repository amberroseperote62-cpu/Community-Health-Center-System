@extends('layouts.app')

@section('title', $patient->full_name)
@section('subtitle', 'Patient Profile')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ===== Patient Info Card ===== --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-4 mb-5">
            <div class="w-16 h-16 rounded-full bg-teal-100 flex items-center justify-center text-2xl font-bold text-teal-700">
                {{ strtoupper(substr($patient->first_name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $patient->full_name }}</h2>
                <p class="text-sm text-gray-500">Patient ID: #{{ $patient->id }}</p>
            </div>
        </div>

        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-500">Gender</dt>
                <dd class="font-medium text-gray-800">{{ $patient->gender }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Birthdate</dt>
                <dd class="font-medium text-gray-800">{{ $patient->birthdate->format('F d, Y') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Age</dt>
                <dd class="font-medium text-gray-800">{{ $patient->birthdate->age }} years old</dd>
            </div>
            <div>
                <dt class="text-gray-500 mb-1">Address</dt>
                <dd class="font-medium text-gray-800">{{ $patient->address }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Contact</dt>
                <dd class="font-medium text-gray-800">{{ $patient->contact_number }}</dd>
            </div>
            @if($patient->emergency_contact)
            <div>
                <dt class="text-gray-500 mb-1">Emergency Contact</dt>
                <dd class="font-medium text-gray-800">{{ $patient->emergency_contact }}</dd>
            </div>
            @endif
        </dl>

        <div class="flex gap-2 mt-5 pt-4 border-t border-gray-100">
            <a href="{{ route('patients.edit', $patient) }}"
                class="flex-1 text-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                Edit
            </a>
            @if(auth()->user()->isAdmin())
            <button type="button"
                data-delete-url="{{ route('patients.destroy', $patient) }}"
                data-delete-msg="Delete {{ $patient->full_name }}? This is a soft-delete and can be recovered."
                class="flex-1 text-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                Delete
            </button>
            @endif
        </div>
    </div>

    {{-- ===== History ===== --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Appointment History --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Appointment History</h3>
                <span class="text-xs text-gray-400">{{ $patient->appointments->count() }} total</span>
            </div>
            <div class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
                @forelse($patient->appointments as $appt)
                <div class="px-5 py-3 flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $appt->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</p>
                        <p class="text-xs text-gray-500">{{ $appt->healthWorker->full_name }} &mdash; {{ $appt->reason }}</p>
                    </div>
                    @php
                        $colors = ['pending' => 'amber', 'confirmed' => 'blue', 'completed' => 'green', 'cancelled' => 'red'];
                        $c = $colors[$appt->status] ?? 'gray';
                    @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $c }}-100 text-{{ $c }}-700 capitalize">
                        {{ $appt->status }}
                    </span>
                </div>
                @empty
                <p class="px-5 py-8 text-sm text-gray-400 text-center">No appointments recorded.</p>
                @endforelse
            </div>
        </div>

        {{-- Consultation History --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Consultation History</h3>
                <span class="text-xs text-gray-400">{{ $patient->consultations->count() }} total</span>
            </div>
            <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
                @forelse($patient->consultations as $consult)
                <div class="px-5 py-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-gray-800">{{ $consult->consultation_date->format('M d, Y') }}</p>
                        <span class="text-xs text-gray-500">Dr. {{ $consult->healthWorker->full_name }}</span>
                    </div>
                    <div class="text-xs text-gray-600 space-y-1">
                        <p><span class="font-medium">Symptoms:</span> {{ $consult->symptoms }}</p>
                        <p><span class="font-medium">Diagnosis:</span> {{ $consult->diagnosis }}</p>
                        <p><span class="font-medium">Treatment:</span> {{ $consult->treatment_notes }}</p>
                    </div>
                    @if($consult->medicines->isNotEmpty())
                    <div class="mt-2 flex flex-wrap gap-1">
                        @foreach($consult->medicines as $med)
                        <span class="px-2 py-0.5 bg-teal-50 text-teal-700 text-xs rounded-full">
                            {{ $med->name }} ({{ $med->pivot->quantity }} units)
                        </span>
                        @endforeach
                    </div>
                    @endif
                    <div class="mt-2">
                        <a href="{{ route('consultations.show', $consult) }}"
                            class="text-xs text-teal-600 hover:text-teal-700 font-medium">View full details →</a>
                    </div>
                </div>
                @empty
                <p class="px-5 py-8 text-sm text-gray-400 text-center">No consultations recorded.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
