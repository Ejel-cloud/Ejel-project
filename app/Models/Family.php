<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Family extends Model
{
    use HasFactory;

    protected $table = 'family_histories';  // <<< هنا تحديد اسم الجدول الصحيح

    protected $fillable = [
        'animal_id',
        'relation_type',
        'description',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function media()
    {
        return $this->morphMany(AnimalMedia::class, 'imageable');
    }
}
