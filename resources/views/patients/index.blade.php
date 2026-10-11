@extends('layouts.app')

@section('title', 'Patients')
@section('subtitle', 'Manage patient records')

@section('content')

<div class="flex items-center justify-between mb-6">
    <form method="GET" action="{{ route('patients.index') }}" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name..."
            id="patient-search"
            class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 w-64">
        <button type="submit"
            class="px-4 py-2 text-sm font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
            Search
        </button>
        @if($search)
        <a href="{{ route('patients.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
        @endif
    </form>
    <a href="{{ route('patients.create') }}"
        id="btn-add-patient"
        class="flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Patient
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gender</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Birthdate</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($patients as $patient)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3 text-gray-400 text-xs">{{ $patient->id }}</td>
                <td class="px-5 py-3">
                    <div class="font-medium text-gray-800">{{ $patient->full_name }}</div>
                    <div class="text-xs text-gray-400 truncate max-w-xs">{{ $patient->address }}</div>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $patient->gender }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $patient->birthdate->format('M d, Y') }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $patient->contact_number }}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('patients.show', $patient) }}"
                            class="px-3 py-1.5 text-xs font-medium text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-lg transition-colors">
                            View
                        </a>
                        <a href="{{ route('patients.edit', $patient) }}"
                            class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            Edit
                        </a>
                        @if(auth()->user()->isAdmin())
                        <button type="button"
                            data-delete-url="{{ route('patients.destroy', $patient) }}"
                            data-delete-msg="Are you sure you want to delete {{ $patient->full_name }}? This will soft-delete the record."
                            class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                            Delete
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    No patients found{{ $search ? ' for "' . $search . '"' : '' }}.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($patients->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $patients->links() }}
    </div>
    @endif
</div>

@endsection
