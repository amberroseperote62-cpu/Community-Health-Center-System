<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicine extends Model
{
    use HasFactory;

    /**
     * Fields that can be mass-assigned.
     */
    protected $fillable = [
        'name',
        'generic_name',
        'category',
        'dosage_form',
        'stock_quantity',
        'reorder_level',
        'expiry_date',
    ];

    /**
     * Cast expiry_date to a Carbon date.
     */
    protected $casts = [
        'expiry_date' => 'date',
    ];

    /**
     * Check if the medicine is low in stock.
     * Returns true when stock is at or below reorder level.
     */
    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    /**
     * Check if the medicine is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }

    /**
     * Check if the medicine expires within 30 days.
     */
    public function isNearExpiry(): bool
    {
        return !$this->isExpired() && $this->expiry_date->diffInDays(now()) <= 30;
    }

    /**
     * A medicine can be prescribed across consultations.
     */
    public function consultations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Consultation::class);
    }
}
