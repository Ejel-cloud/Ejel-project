<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'animal_id',
        'disease_name',
        'symptoms',
        'diagnosis_at',
        'treatment',
        'recovery_date',
        'veterinarian',
        'is_cured'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    // app/Models/Animal.php

    public function media()
    {
        return $this->morphMany(AnimalMedia::class, 'imageable');
    }
}
