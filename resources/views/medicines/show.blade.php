@extends('layouts.app')

@section('title', $medicine->name)
@section('subtitle', $medicine->category . ' — ' . ucfirst($medicine->dosage_form))

@section('content')

<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        {{-- Stock / Expiry Alerts --}}
        @if($medicine->isExpired())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 font-medium flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            This medicine has expired.
        </div>
        @elseif($medicine->isNearExpiry())
        <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700 font-medium flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Expiring soon — {{ $medicine->expiry_date->diffForHumans() }}.
        </div>
        @endif

        @if($medicine->isLowStock())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 font-medium flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Low stock — only {{ $medicine->stock_quantity }} units remaining (reorder at {{ $medicine->reorder_level }}).
        </div>
        @endif

        <dl class="space-y-4 text-sm">
            <div class="flex justify-between border-b border-gray-50 pb-3">
                <dt class="text-gray-500">Brand Name</dt>
                <dd class="font-semibold text-gray-800">{{ $medicine->name }}</dd>
            </div>
            <div class="flex justify-between border-b border-gray-50 pb-3">
                <dt class="text-gray-500">Generic Name</dt>
                <dd class="font-medium text-gray-800">{{ $medicine->generic_name }}</dd>
            </div>
            <div class="flex justify-between border-b border-gray-50 pb-3">
                <dt class="text-gray-500">Category</dt>
                <dd class="font-medium text-gray-800">{{ $medicine->category }}</dd>
            </div>
            <div class="flex justify-between border-b border-gray-50 pb-3">
                <dt class="text-gray-500">Dosage Form</dt>
                <dd class="font-medium text-gray-800 capitalize">{{ $medicine->dosage_form }}</dd>
            </div>
            <div class="flex justify-between border-b border-gray-50 pb-3">
                <dt class="text-gray-500">Stock Quantity</dt>
                <dd class="font-bold {{ $medicine->isLowStock() ? 'text-red-600' : 'text-gray-800' }}">{{ $medicine->stock_quantity }} units</dd>
            </div>
            <div class="flex justify-between border-b border-gray-50 pb-3">
                <dt class="text-gray-500">Reorder Level</dt>
                <dd class="font-medium text-gray-800">{{ $medicine->reorder_level }} units</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Expiry Date</dt>
                <dd class="font-medium {{ $medicine->isExpired() ? 'text-red-600' : ($medicine->isNearExpiry() ? 'text-amber-600' : 'text-gray-800') }}">
                    {{ $medicine->expiry_date->format('F d, Y') }}
                </dd>
            </div>
        </dl>

        <div class="flex gap-2 mt-5 pt-4 border-t border-gray-100">
            <a href="{{ route('medicines.edit', $medicine) }}"
                class="flex-1 text-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Edit</a>
            @if(auth()->user()->isAdmin())
            <button type="button"
                data-delete-url="{{ route('medicines.destroy', $medicine) }}"
                data-delete-msg="Remove {{ $medicine->name }} from inventory?"
                class="flex-1 text-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
            @endif
        </div>
        <div class="mt-4">
            <a href="{{ route('medicines.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back to medicines</a>
        </div>
    </div>
</div>

@endsection
