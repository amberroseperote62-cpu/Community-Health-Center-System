<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, SoftDeletes; // SoftDeletes hides records instead of permanently deleting

    /**
     * Fields that can be mass-assigned (filled via form).
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birthdate',
        'gender',
        'address',
        'contact_number',
        'emergency_contact',
    ];

    /**
     * Cast birthdate to a Carbon date for easy formatting.
     */
    protected $casts = [
        'birthdate' => 'date',
    ];

    /**
     * Get the patient's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * A patient can have many appointments.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * A patient can have many consultations.
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }
}
