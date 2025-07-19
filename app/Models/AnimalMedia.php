<?php

// app/Models/AnimalMedia.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnimalMedia extends Model
{
    use HasFactory;

    protected $fillable = [
    'file_path',
    'imageable_id',
    'imageable_type'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
