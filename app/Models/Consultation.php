<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Consultation extends Model
{
    use HasFactory;

    /**
     * Fields that can be mass-assigned.
     */
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'health_worker_id',
        'consultation_date',
        'symptoms',
        'diagnosis',
        'treatment_notes',
    ];

    /**
     * Cast consultation_date to a Carbon date.
     */
    protected $casts = [
        'consultation_date' => 'date',
    ];

    /**
     * A consultation may be linked to an appointment (nullable).
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * A consultation belongs to one patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * A consultation belongs to one health worker.
     */
    public function healthWorker(): BelongsTo
    {
        return $this->belongsTo(HealthWorker::class);
    }

    /**
     * A consultation can have many prescribed medicines (many-to-many).
     * The pivot table stores quantity and dosage_instructions.
     */
    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class, 'consultation_medicine')
            ->withPivot('quantity', 'dosage_instructions')
            ->withTimestamps();
    }
}
