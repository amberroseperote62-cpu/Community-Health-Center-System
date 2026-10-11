<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id'       => 'required|exists:patients,id',
            'health_worker_id' => 'required|exists:health_workers,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'reason'           => 'required|string|max:500',
            'status'           => 'required|in:pending,confirmed,completed,cancelled',
            // Prevent double-booking: same health worker, date, and time must be unique
            'appointment_time' => [
                'required',
                'date_format:H:i',
                Rule::unique('appointments')->where(function ($query) {
                    return $query
                        ->where('health_worker_id', $this->health_worker_id)
                        ->where('appointment_date', $this->appointment_date);
                }),
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
