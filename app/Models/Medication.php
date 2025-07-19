<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'name',
        'date_given',
        'notes',
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
