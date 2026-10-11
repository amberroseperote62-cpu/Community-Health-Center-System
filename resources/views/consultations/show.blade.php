@extends('layouts.app')

@section('title', 'Consultation Details')
@section('subtitle', $consultation->patient->full_name . ' — ' . $consultation->consultation_date->format('M d, Y'))

@section('content')

<div class="max-w-3xl space-y-5">

    {{-- Main info --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-semibold text-gray-800">Consultation Record</h2>
            <div class="flex gap-2">
                <a href="{{ route('consultations.edit', $consultation) }}"
                    class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Edit</a>
                @if(auth()->user()->isAdmin())
                <button type="button"
                    data-delete-url="{{ route('consultations.destroy', $consultation) }}"
                    data-delete-msg="Delete this consultation? Medicine stock will be restored."
                    class="px-4 py-2 text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                @endif
            </div>
        </div>

        <dl class="grid grid-cols-2 gap-5 text-sm mb-6">
            <div>
                <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Patient</dt>
                <dd class="font-semibold text-gray-800">
                    <a href="{{ route('patients.show', $consultation->patient) }}" class="text-teal-600 hover:underline">
                        {{ $consultation->patient->full_name }}
                    </a>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Health Worker</dt>
                <dd class="font-semibold text-gray-800">{{ $consultation->healthWorker->full_name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Date</dt>
                <dd class="font-semibold text-gray-800">{{ $consultation->consultation_date->format('F d, Y') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Linked Appointment</dt>
                <dd class="font-semibold text-gray-800">
                    @if($consultation->appointment)
                    <a href="{{ route('appointments.show', $consultation->appointment) }}" class="text-teal-600 hover:underline">
                        View appointment →
                    </a>
                    @else
                    <span class="text-gray-400">Walk-in</span>
                    @endif
                </dd>
            </div>
        </dl>

        <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Symptoms</h4>
                <p class="text-sm text-gray-800">{{ $consultation->symptoms }}</p>
            </div>
            <div class="bg-amber-50 border border-amber-100 rounded-lg p-4">
                <h4 class="text-xs font-semibold text-amber-700 uppercase tracking-wider mb-2">Diagnosis</h4>
                <p class="text-sm text-gray-800 font-medium">{{ $consultation->diagnosis }}</p>
            </div>
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                <h4 class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-2">Treatment Notes</h4>
                <p class="text-sm text-gray-800">{{ $consultation->treatment_notes }}</p>
            </div>
        </div>
    </div>

    {{-- Prescriptions --}}
    @if($consultation->medicines->isNotEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Prescriptions</h3>
        <div class="space-y-3">
            @foreach($consultation->medicines as $med)
            <div class="flex items-center justify-between p-3 bg-teal-50 border border-teal-100 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $med->name }}</p>
                    <p class="text-xs text-gray-500">{{ $med->generic_name }} &mdash; {{ $med->pivot->dosage_instructions }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-teal-700">{{ $med->pivot->quantity }} units</p>
                    <p class="text-xs text-gray-400">{{ ucfirst($med->dosage_form) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <a href="{{ route('consultations.index') }}" class="text-sm text-gray-500 hover:text-gray-700 inline-block">← Back to consultations</a>
</div>

@endsection
