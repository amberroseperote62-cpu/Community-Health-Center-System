@extends('layouts.app')

@section('title', 'Health Workers')
@section('subtitle', 'Manage clinic staff')

@section('content')

<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('health-workers.index') }}" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or position..."
            id="hw-search"
            class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 w-72">
        <button type="submit" class="px-4 py-2 text-sm font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">Search</button>
        @if($search)
        <a href="{{ route('health-workers.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
        @endif
    </form>
    <a href="{{ route('health-workers.create') }}"
        id="btn-add-hw"
        class="flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Health Worker
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Position</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Specialization</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($healthWorkers as $hw)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $hw->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ $hw->email }}</p>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $hw->position }}</td>
                <td class="px-5 py-3 text-gray-500 text-xs">{{ $hw->specialization ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $hw->contact_number }}</td>
                <td class="px-5 py-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $hw->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($hw->status) }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('health-workers.show', $hw) }}"
                            class="px-3 py-1.5 text-xs font-medium text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition-colors">View</a>
                        <a href="{{ route('health-workers.edit', $hw) }}"
                            class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Edit</a>
                        @if(auth()->user()->isAdmin())
                        <button type="button"
                            data-delete-url="{{ route('health-workers.destroy', $hw) }}"
                            data-delete-msg="Delete {{ $hw->full_name }}? Their appointment records will also be removed."
                            class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-400">No health workers found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($healthWorkers->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $healthWorkers->links() }}</div>
    @endif
</div>

@endsection
