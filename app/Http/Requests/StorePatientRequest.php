<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation rules for creating a new patient.
 */
class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authentication is handled by route middleware
    }

    public function rules(): array
    {
        return [
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'birthdate'         => 'required|date|before:today',
            'gender'            => 'required|in:Male,Female,Other',
            'address'           => 'required|string|max:500',
            'contact_number'    => 'required|string|max:20',
            'emergency_contact' => 'nullable|string|max:100',
        ];
    }
}
