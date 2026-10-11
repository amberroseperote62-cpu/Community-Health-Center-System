<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_id'    => 'nullable|exists:appointments,id',
            'patient_id'        => 'required|exists:patients,id',
            'health_worker_id'  => 'required|exists:health_workers,id',
            'consultation_date' => 'required|date',
            'symptoms'          => 'required|string|max:1000',
            'diagnosis'         => 'required|string|max:1000',
            'treatment_notes'   => 'required|string|max:2000',
            'medicines'                       => 'nullable|array',
            'medicines.*.medicine_id'         => 'required_with:medicines|exists:medicines,id',
            'medicines.*.quantity'            => 'required_with:medicines|integer|min:1',
            'medicines.*.dosage_instructions' => 'required_with:medicines|string|max:255',
        ];
    }
}
