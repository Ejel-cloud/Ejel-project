<?php

namespace App\Policies;

use App\Models\MarketplaceChat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketplaceChatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view a chat.
     */
    public function view(User $user, MarketplaceChat $chat): bool
    {
        // If it's a public chat, allow everyone
        if ($chat->type === 'public') {
            return true;
        }

        // If it's private, only allow users who are part of the chat
        return $chat->users()->where('user_id', $user->id)->exists();
    }
}
