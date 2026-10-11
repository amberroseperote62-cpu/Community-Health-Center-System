<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $appointmentId = $this->route('appointment')->id;

        return [
            'patient_id'       => 'required|exists:patients,id',
            'health_worker_id' => 'required|exists:health_workers,id',
            'appointment_date' => 'required|date',
            'reason'           => 'required|string|max:500',
            'status'           => 'required|in:pending,confirmed,completed,cancelled',
            // Exclude current appointment from double-booking check
            'appointment_time' => [
                'required',
                'date_format:H:i',
                Rule::unique('appointments')->where(function ($query) {
                    return $query
                        ->where('health_worker_id', $this->health_worker_id)
                        ->where('appointment_date', $this->appointment_date);
                })->ignore($appointmentId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'appointment_time.unique' => 'This health worker is already booked at the selected date and time.',
        ];
    }
}
