@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . auth()->user()->name)

@section('content')

{{-- ===== Summary Cards ===== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    {{-- Total Patients --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalPatients }}</p>
            <p class="text-sm text-gray-500">Total Patients</p>
        </div>
    </div>

    {{-- Active Health Workers --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalHealthWorkers }}</p>
            <p class="text-sm text-gray-500">Active Health Workers</p>
        </div>
    </div>

    {{-- Today's Appointments --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $todayAppointments }}</p>
            <p class="text-sm text-gray-500">Today's Appointments</p>
        </div>
    </div>

    {{-- Pending Appointments --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $pendingAppointments }}</p>
            <p class="text-sm text-gray-500">Pending Appointments</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ===== Today's Appointment List ===== --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Today's Appointments</h2>
            <a href="{{ route('appointments.index', ['date' => today()->toDateString()]) }}"
               class="text-sm text-teal-600 hover:text-teal-700 font-medium">View all</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($todayAppointmentList as $appt)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $appt->patient->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ $appt->healthWorker->full_name }} &mdash; {{ $appt->reason }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('g:i A') }}</span>
                    @php
                        $colors = ['pending' => 'amber', 'confirmed' => 'blue', 'completed' => 'green', 'cancelled' => 'red'];
                        $c = $colors[$appt->status] ?? 'gray';
                    @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $c }}-100 text-{{ $c }}-700 capitalize">
                        {{ $appt->status }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-gray-400">
                No appointments scheduled for today.
            </div>
            @endforelse
        </div>
    </div>

    {{-- ===== Medicine Alerts ===== --}}
    <div class="space-y-5">

        {{-- Low Stock --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                <h2 class="font-semibold text-gray-800 text-sm">Low Stock Medicines</h2>
                <span class="ml-auto text-xs bg-red-100 text-red-600 font-medium px-2 py-0.5 rounded-full">{{ $lowStockMedicines->count() }}</span>
            </div>
            <div class="divide-y divide-gray-50 max-h-48 overflow-y-auto">
                @forelse($lowStockMedicines as $med)
                <div class="px-5 py-2.5 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-800">{{ $med->name }}</p>
                        <p class="text-xs text-gray-400">Reorder: {{ $med->reorder_level }} units</p>
                    </div>
                    <span class="text-xs font-bold text-red-600">{{ $med->stock_quantity }} left</span>
                </div>
                @empty
                <p class="px-5 py-4 text-xs text-gray-400 text-center">All medicines are well-stocked.</p>
                @endforelse
            </div>
        </div>

        {{-- Expiring / Expired --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                <h2 class="font-semibold text-gray-800 text-sm">Expiry Alerts</h2>
                <span class="ml-auto text-xs bg-amber-100 text-amber-600 font-medium px-2 py-0.5 rounded-full">
                    {{ $expiringSoonMedicines->count() + $expiredMedicines->count() }}
                </span>
            </div>
            <div class="divide-y divide-gray-50 max-h-48 overflow-y-auto">
                @foreach($expiredMedicines as $med)
                <div class="px-5 py-2.5 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-800">{{ $med->name }}</p>
                        <p class="text-xs text-red-500">Expired {{ $med->expiry_date->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-medium">Expired</span>
                </div>
                @endforeach
                @foreach($expiringSoonMedicines as $med)
                <div class="px-5 py-2.5 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-800">{{ $med->name }}</p>
                        <p class="text-xs text-amber-600">Expires {{ $med->expiry_date->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Soon</span>
                </div>
                @endforeach
                @if($expiringSoonMedicines->isEmpty() && $expiredMedicines->isEmpty())
                <p class="px-5 py-4 text-xs text-gray-400 text-center">No expiry alerts.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
