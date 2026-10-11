<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthWorker extends Model
{
    use HasFactory;

    /**
     * Fields that can be mass-assigned.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'specialization',
        'contact_number',
        'email',
        'status',
    ];

    /**
     * Get the health worker's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * A health worker can handle many appointments.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * A health worker can have many consultations.
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }
}
