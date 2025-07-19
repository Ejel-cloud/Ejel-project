<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Vaccine extends Model
{
    protected $fillable = [
        'animal_id',
        'vaccine_type',
        'vaccination_date',
        'next_vaccination_date',
        'veterinarian',
        'notes',
        'name'
    ];

    protected $casts = [
        'vaccination_date' => 'date',
        'next_vaccination_date' => 'date',
    ];

    /**
     * The animal that received the vaccine.
     */
    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    /**
     * Polymorphic relation to images.
     */
    public function media(): MorphMany
    {
        return $this->morphMany(AnimalMedia::class, 'imageable');
    }

    /**
     * Scope to get upcoming vaccination reminders.
     */
    public function scopeUpcoming($query, $days = 7)
    {
        return $query->whereDate('next_vaccination_date', '<=', now()->addDays($days));
    }
}
