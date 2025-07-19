<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Animal extends Model
{
    use HasFactory;

    /*----------------------------------------------------------------------
     | Mass‑assignable attributes
     *---------------------------------------------------------------------*/
    protected $fillable = [
        'type',          // animal type (cow, sheep, etc.)
        'gender',        // male | female
        'birth_date',    // date of birth
        'notes',
        'user_id',
        'file_path',     // main photo
        'health_status',
        'price',
        'for_sale',
        'city',
    ];

    /*----------------------------------------------------------------------
     | Default attributes
     *---------------------------------------------------------------------*/
    protected $attributes = [
        'for_sale'      => false,
        'health_status' => 'Good',
    ];

    /*----------------------------------------------------------------------
     | Casts
     *---------------------------------------------------------------------*/
    protected $casts = [
        'birth_date' => 'date',
        'for_sale'   => 'boolean',
    ];

    /*----------------------------------------------------------------------
     | Relationships
     *---------------------------------------------------------------------*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->morphMany(AnimalMedia::class, 'imageable');
    }

    public function families()
    {
        return $this->hasMany(Family::class);
    }

    public function diseases()
    {
        return $this->hasMany(Disease::class);
    }

    public function vaccines()
    {
        return $this->hasMany(Vaccine::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    /*----------------------------------------------------------------------
     | Query scopes
     *---------------------------------------------------------------------*/
    public function scopeForCurrentUser(Builder $query): Builder
    {
        return $query->where('user_id', Auth::id());
    }

    public function scopeMale(Builder $query): Builder
    {
        return $query->where('gender', 'male');
    }

    public function scopeFemale(Builder $query): Builder
    {
        return $query->where('gender', 'female');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeForSale(Builder $query): Builder
    {
        return $query->where('for_sale', true);
    }

    public function scopeInCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    /*----------------------------------------------------------------------
     | Accessors & helpers
     *---------------------------------------------------------------------*/

    /**
     * Age as a decimal in years with one decimal place, e.g. "2.5 yrs".
     */
    public function getAgeAttribute(): string
    {
        if (! $this->birth_date) {
            return 'N/A';
        }

        $totalMonths = $this->birth_date->diffInMonths(Carbon::now());
        $yearsDecimal = round($totalMonths / 12, 1);          // one‑decimal precision

        return $yearsDecimal . ' years';
    }

    public function isForSale(): bool
    {
        return $this->for_sale;
    }

    public function maxImagesAllowed(): int
    {
        return 3;
    }
}
