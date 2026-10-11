@extends('layouts.app')

@section('title', 'Medicines')
@section('subtitle', 'Inventory management')

@section('content')

<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('medicines.index') }}" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or category..."
            id="med-search"
            class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 w-72">
        <button type="submit" class="px-4 py-2 text-sm font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">Search</button>
        @if($search)
        <a href="{{ route('medicines.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
        @endif
    </form>
    <a href="{{ route('medicines.create') }}"
        id="btn-add-med"
        class="flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Medicine
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Form</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Expiry</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($medicines as $med)
            {{-- Highlight entire row if expired --}}
            <tr class="hover:bg-gray-50 transition-colors {{ $med->isExpired() ? 'bg-red-50' : ($med->isNearExpiry() ? 'bg-amber-50' : '') }}">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $med->name }}</p>
                    <p class="text-xs text-gray-400">{{ $med->generic_name }}</p>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $med->category }}</td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs capitalize">{{ $med->dosage_form }}</span>
                </td>
                <td class="px-5 py-3">
                    {{-- Red for low/zero stock, normal otherwise --}}
                    <span class="font-semibold {{ $med->isLowStock() ? 'text-red-600' : 'text-gray-800' }}">
                        {{ $med->stock_quantity }}
                    </span>
                    <span class="text-xs text-gray-400"> / {{ $med->reorder_level }} reorder</span>
                    @if($med->isLowStock())
                    <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs rounded font-medium">Low</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-gray-600">
                    {{ $med->expiry_date->format('M d, Y') }}
                    @if($med->isExpired())
                    <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs rounded font-medium">Expired</span>
                    @elseif($med->isNearExpiry())
                    <span class="ml-1 px-1.5 py-0.5 bg-amber-100 text-amber-600 text-xs rounded font-medium">Soon</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('medicines.show', $med) }}"
                            class="px-3 py-1.5 text-xs font-medium text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition-colors">View</a>
                        <a href="{{ route('medicines.edit', $med) }}"
                            class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Edit</a>
                        @if(auth()->user()->isAdmin())
                        <button type="button"
                            data-delete-url="{{ route('medicines.destroy', $med) }}"
                            data-delete-msg="Remove {{ $med->name }} from inventory?"
                            class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No medicines found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($medicines->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $medicines->links() }}</div>
    @endif
</div>

@endsection
