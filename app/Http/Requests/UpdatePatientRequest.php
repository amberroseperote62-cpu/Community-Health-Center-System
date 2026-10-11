<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation rules for updating a patient record.
 * Same as store but allows current record's unique fields.
 */
class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
