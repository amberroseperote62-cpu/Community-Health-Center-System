@extends('layouts.app')

@section('title', 'Consultations')
@section('subtitle', 'Medical visit records')

@section('content')

<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('consultations.index') }}" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by patient name..."
            id="consult-search"
            class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 w-64">
        <button type="submit" class="px-4 py-2 text-sm font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">Search</button>
        @if($search)
        <a href="{{ route('consultations.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
        @endif
    </form>
    <a href="{{ route('consultations.create') }}"
        id="btn-add-consult"
        class="flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Record Consultation
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Patient</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Health Worker</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Diagnosis</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($consultations as $consult)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $consult->patient->full_name }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $consult->healthWorker->full_name }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $consult->consultation_date->format('M d, Y') }}</td>
                <td class="px-5 py-3 text-gray-600 max-w-xs truncate">{{ $consult->diagnosis }}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('consultations.show', $consult) }}"
                            class="px-3 py-1.5 text-xs font-medium text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition-colors">View</a>
                        <a href="{{ route('consultations.edit', $consult) }}"
                            class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">Edit</a>
                        @if(auth()->user()->isAdmin())
                        <button type="button"
                            data-delete-url="{{ route('consultations.destroy', $consult) }}"
                            data-delete-msg="Delete this consultation for {{ $consult->patient->full_name }}? Stock will be restored."
                            class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">No consultations found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($consultations->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $consultations->links() }}</div>
    @endif
</div>

@endsection
