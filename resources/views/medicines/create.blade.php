@extends('layouts.app')

@section('title', 'Add Medicine')
@section('subtitle', 'Add to medicine inventory')

@section('content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @include('partials.errors')

        <form method="POST" action="{{ route('medicines.store') }}" id="create-med-form">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="med_name" value="{{ old('name') }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('name') border-red-400 @enderror"
                        placeholder="e.g. Paracetamol 500mg">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Generic Name <span class="text-red-500">*</span></label>
                    <input type="text" name="generic_name" id="med_generic" value="{{ old('generic_name') }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('generic_name') border-red-400 @enderror"
                        placeholder="e.g. Paracetamol">
                    @error('generic_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <input type="text" name="category" id="med_category" value="{{ old('category') }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('category') border-red-400 @enderror"
                        placeholder="e.g. Antibiotic, Vitamin">
                    @error('category')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dosage Form <span class="text-red-500">*</span></label>
                    <select name="dosage_form" id="med_form"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('dosage_form') border-red-400 @enderror">
                        <option value="">Select form</option>
                        @foreach(['tablet', 'syrup', 'capsule', 'injection', 'ointment', 'drops', 'other'] as $form)
                        <option value="{{ $form }}" {{ old('dosage_form') == $form ? 'selected' : '' }}>{{ ucfirst($form) }}</option>
                        @endforeach
                    </select>
                    @error('dosage_form')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_quantity" id="med_stock" value="{{ old('stock_quantity', 0) }}" min="0"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('stock_quantity') border-red-400 @enderror">
                    @error('stock_quantity')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Level <span class="text-red-500">*</span></label>
                    <input type="number" name="reorder_level" id="med_reorder" value="{{ old('reorder_level', 10) }}" min="0"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('reorder_level') border-red-400 @enderror">
                    <p class="mt-1 text-xs text-gray-400">Alert shown when stock reaches this level</p>
                    @error('reorder_level')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date <span class="text-red-500">*</span></label>
                    <input type="date" name="expiry_date" id="med_expiry" value="{{ old('expiry_date') }}"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('expiry_date') border-red-400 @enderror">
                    @error('expiry_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors">Add Medicine</button>
                <a href="{{ route('medicines.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
