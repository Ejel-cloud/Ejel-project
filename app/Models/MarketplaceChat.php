<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceChat extends Model
{
    use HasFactory;
    protected $fillable = [
        'listing_id',
        'type',
    ];

    // ...

    // صِحّح هنا المفتاح الخارجي أيضاً
    public function messages()
    {
        return $this->hasMany(MarketplaceMessage::class, 'chat_id');
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'marketplace_chat_user',         // اسم الجدول الوسيط
            'marketplace_chat_id',           // FK لـ شات
            'user_id'                        // FK لـ مستخدم
        );
    }


    public function listing()
    {
        return $this->belongsTo(MarketplaceListing::class);
    }
}
