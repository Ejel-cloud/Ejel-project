<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceChat;
use App\Models\MarketplaceListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceChatController extends Controller
{
    /**
     * عرض الشات العام للإعلان
     */
    public function publicChat(MarketplaceListing $listing)
    {
        // جلب أو إنشاء غرفة الشات العام
        $chat = MarketplaceChat::firstOrCreate([
            'listing_id' => $listing->id,
            'type'       => 'public',
        ]);

        // حمّل الرسائل مع بيانات المرسل
        $chat->load('messages.user');

        // مرّر الـ $chat و $listing إلى الـ View
        return view('marketplace.chat.public', compact('listing', 'chat'));
    }

    /**
     * عرض الشات الخاص بين المشتري والبائع
     */
    public function privateChat(MarketplaceListing $listing)
    {
        // جلب أو إنشاء غرفة الشات الخاص
        $chat = MarketplaceChat::firstOrCreate([
            'listing_id' => $listing->id,
            'type'       => 'private',
        ]);

        // إذا المستخدم لم يُضَم بعد لغرفة الشات الخاص، أضفه
        if (! $chat->users()->where('user_id', Auth::id())->exists()) {
            $chat->users()->attach(Auth::id());
        }

        // حمّل الرسائل مع بيانات المرسل
        $chat->load('messages.user');

        return view('marketplace.chat.private', compact('listing', 'chat'));
    }

    /**
     * حفظ رسالة جديدة في أي شات (عام أو خاص)
     */
    public function sendMessage(Request $request, MarketplaceChat $chat)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // إنشاء الرسالة
        $chat->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }
}
