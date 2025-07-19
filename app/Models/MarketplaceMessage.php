<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarketplaceMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',       // ID غرفة الشات
        'user_id',       // مرسل الرسالة
        'message',       // نص الرسالة
    ];

    public function chat()
    {
        return $this->belongsTo(MarketplaceChat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
