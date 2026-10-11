{{-- Reusable component: displays validation error messages below a form field --}}
@if ($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
    <div class="flex items-center gap-2 text-red-700 font-medium text-sm mb-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Please fix the following errors:
    </div>
    <ul class="text-sm text-red-600 list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
