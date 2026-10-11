<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHealthWorkerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ignore current health worker's email in the unique check
        $id = $this->route('health_worker')->id;

        return [
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'position'       => 'required|in:Doctor,Nurse,Midwife,Barangay Health Worker',
            'specialization' => 'nullable|string|max:100',
            'contact_number' => 'required|string|max:20',
            'email'          => "required|email|unique:health_workers,email,{$id}",
            'status'         => 'required|in:active,inactive',
        ];
    }
}
