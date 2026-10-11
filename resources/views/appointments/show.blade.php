@extends('layouts.app')

@section('title', 'Appointment Details')
@section('subtitle', $appointment->patient->full_name . ' — ' . $appointment->appointment_date->format('M d, Y'))

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @php $c = ['pending'=>'amber','confirmed'=>'blue','completed'=>'green','cancelled'=>'red'][$appointment->status] ?? 'gray'; @endphp

        <div class="flex items-center justify-between mb-6">
            <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $c }}-100 text-{{ $c }}-700 capitalize">
                {{ $appointment->status }}
            </span>
            <div class="flex gap-2">
                <a href="{{ route('appointments.edit', $appointment) }}"
                    class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Edit</a>
                @if(auth()->user()->isAdmin())
                <button type="button"
                    data-delete-url="{{ route('appointments.destroy', $appointment) }}"
                    data-delete-msg="Delete this appointment?"
                    class="px-4 py-2 text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                @endif
            </div>
        </div>

        <dl class="grid grid-cols-2 gap-5 text-sm">
            <div>
                <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Patient</dt>
                <dd class="font-semibold text-gray-800">
                    <a href="{{ route('patients.show', $appointment->patient) }}" class="text-teal-600 hover:underline">
                        {{ $appointment->patient->full_name }}
                    </a>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Health Worker</dt>
                <dd class="font-semibold text-gray-800">{{ $appointment->healthWorker->full_name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Date</dt>
                <dd class="font-semibold text-gray-800">{{ $appointment->appointment_date->format('F d, Y') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Time</dt>
                <dd class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-gray-500 text-xs uppercase tracking-wider mb-1">Reason</dt>
                <dd class="text-gray-800">{{ $appointment->reason }}</dd>
            </div>
        </dl>

        {{-- Linked Consultation --}}
        @if($appointment->consultation)
        <div class="mt-6 pt-5 border-t border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Linked Consultation</h3>
            <div class="bg-teal-50 border border-teal-100 rounded-lg p-4 text-sm">
                <p class="font-medium text-gray-800">{{ $appointment->consultation->diagnosis }}</p>
                <p class="text-gray-500 text-xs mt-1">{{ $appointment->consultation->consultation_date->format('M d, Y') }}</p>
                <a href="{{ route('consultations.show', $appointment->consultation) }}"
                    class="text-teal-600 text-xs font-medium hover:underline mt-2 inline-block">
                    View consultation →
                </a>
            </div>
        </div>
        @else
        <div class="mt-6 pt-5 border-t border-gray-100">
            <a href="{{ route('consultations.create') }}"
                class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                + Record consultation for this appointment
            </a>
        </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('appointments.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back to appointments</a>
        </div>
    </div>
</div>

@endsection
