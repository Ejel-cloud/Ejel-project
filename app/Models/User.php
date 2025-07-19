<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // 🔹 أضف هذا

class User extends Authenticatable
{
    // 🔹 أضف HasApiTokens هنا
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password',
        'avatar_path','city','bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
    public function marketplaceChats()
    {
        return $this->belongsToMany(MarketplaceChat::class, 'marketplace_chat_user');
    }
    public function listings()
    {
        return $this->hasMany(MarketplaceListing::class, 'user_id');
    }
}
