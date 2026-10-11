<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    /**
     * Fields that can be mass-assigned.
     */
    protected $fillable = [
        'patient_id',
        'health_worker_id',
        'appointment_date',
        'appointment_time',
        'reason',
        'status',
    ];

    /**
     * Cast date and time fields to proper types.
     */
    protected $casts = [
        'appointment_date' => 'date',
    ];

    /**
     * An appointment belongs to one patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * An appointment belongs to one health worker.
     */
    public function healthWorker(): BelongsTo
    {
        return $this->belongsTo(HealthWorker::class);
    }

    /**
     * An appointment may result in one consultation.
     */
    public function consultation(): HasOne
    {
        return $this->hasOne(Consultation::class);
    }
}
