<?php

namespace App\Policies;

use App\Models\MarketplaceListing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketplaceListingPolicy
{
    use HandlesAuthorization;

    /**
     * تحقق هل المستخدم يقدر يشوف الإعلان.
     */
    public function view(User $user, MarketplaceListing $listing)
    {
        // يمكن للجميع رؤية الإعلانات النشطة، أو مالك الإعلان.
        return $listing->status === 'active' || $user->id === $listing->user_id;
    }

    /**
     * تحقق هل المستخدم يقدر يعدل الإعلان.
     */
    public function update(User $user, MarketplaceListing $listing)
    {
        return $user->id === $listing->user_id;
    }

    /**
     * تحقق هل المستخدم يقدر يحذف الإعلان.
     */
    public function delete(User $user, MarketplaceListing $listing)
    {
        return $user->id === $listing->user_id;
    }
}
