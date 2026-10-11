@extends('layouts.app')

@section('title', $healthWorker->full_name)
@section('subtitle', $healthWorker->position)

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Info Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-4 mb-5">
            <div class="w-16 h-16 rounded-full bg-teal-100 flex items-center justify-center text-2xl font-bold text-teal-700">
                {{ strtoupper(substr($healthWorker->first_name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $healthWorker->full_name }}</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $healthWorker->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ ucfirst($healthWorker->status) }}
                </span>
            </div>
        </div>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Position</dt><dd class="font-medium text-gray-800">{{ $healthWorker->position }}</dd></div>
            @if($healthWorker->specialization)
            <div class="flex justify-between"><dt class="text-gray-500">Specialization</dt><dd class="font-medium text-gray-800">{{ $healthWorker->specialization }}</dd></div>
            @endif
            <div class="flex justify-between"><dt class="text-gray-500">Contact</dt><dd class="font-medium text-gray-800">{{ $healthWorker->contact_number }}</dd></div>
            <div><dt class="text-gray-500 mb-1">Email</dt><dd class="font-medium text-gray-800 break-all">{{ $healthWorker->email }}</dd></div>
        </dl>
        <div class="flex gap-2 mt-5 pt-4 border-t border-gray-100">
            <a href="{{ route('health-workers.edit', $healthWorker) }}"
                class="flex-1 text-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Edit</a>
            @if(auth()->user()->isAdmin())
            <button type="button"
                data-delete-url="{{ route('health-workers.destroy', $healthWorker) }}"
                data-delete-msg="Delete {{ $healthWorker->full_name }}? This will permanently remove their record."
                class="flex-1 text-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
            @endif
        </div>
    </div>

    <div class="lg:col-span-2 space-y-5">
        {{-- Appointments --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Appointment Schedule</h3>
                <span class="text-xs text-gray-400">{{ $healthWorker->appointments->count() }} total</span>
            </div>
            <div class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
                @forelse($healthWorker->appointments as $appt)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $appt->patient->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $appt->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</p>
                    </div>
                    @php $c = ['pending'=>'amber','confirmed'=>'blue','completed'=>'green','cancelled'=>'red'][$appt->status] ?? 'gray'; @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $c }}-100 text-{{ $c }}-700 capitalize">{{ $appt->status }}</span>
                </div>
                @empty
                <p class="px-5 py-8 text-sm text-gray-400 text-center">No appointments yet.</p>
                @endforelse
            </div>
        </div>
        {{-- Consultations --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Recent Consultations</h3>
                <span class="text-xs text-gray-400">{{ $healthWorker->consultations->count() }} total</span>
            </div>
            <div class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
                @forelse($healthWorker->consultations as $c)
                <div class="px-5 py-3">
                    <div class="flex justify-between items-center">
                        <p class="text-sm font-medium text-gray-800">{{ $c->patient->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $c->consultation_date->format('M d, Y') }}</p>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($c->diagnosis, 60) }}</p>
                </div>
                @empty
                <p class="px-5 py-8 text-sm text-gray-400 text-center">No consultations yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
