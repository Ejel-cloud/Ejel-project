<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarketplaceListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'animal_id',         // nullable, لأن ممكن يكون إعلان بدون حيوان محدد (مثلاً أعلاف)
        'title',             // عنوان الإعلان، مثل: "Sheep for Sale"
        'description',       // تفاصيل الإعلان
        'category',          // 'animal' أو 'feed'
        'animal_type',       // مثل: cow, sheep, horse, camel, goat — يستخدم للفلترة
        'city',              // المدينة السورية
        'price',             // السعر
        'status',            // 'active', 'sold', 'cancelled'
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الحيوان (اختياري)
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    // صور الإعلان (علاقة polymorphic)
    public function media()
    {
        return $this->morphMany(AnimalMedia::class, 'imageable');
    }

    // شات الإعلان العام
    public function publicChat()
    {
        return $this->hasOne(MarketplaceChat::class, 'listing_id')->where('type', 'public');
    }

    // شات الخاص بالبائع والمشتري
    public function privateChats()
    {
        return $this->hasMany(MarketplaceChat::class, 'listing_id')->where('type', 'private');
    }
}
