<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\MarketplaceChat;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| هنا يتم تسجيل قنوات البث الخاصة بك. تحقق من صلاحية المستخدم قبل السماح
| له بالإنضمام للقناة.
|
*/

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // تأكد أن الشات موجود وأن المستخدم عضو فيه
    $chat = MarketplaceChat::find($chatId);
    if (! $chat) {
        return false;
    }
    // تحقق من أن المستخدم مشارك
    return $chat->users()->where('user_id', $user->id)->exists();
});
