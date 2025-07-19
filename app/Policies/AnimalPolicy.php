<?php

namespace App\Policies;

use App\Models\Animal;
use App\Models\User;

class AnimalPolicy
{
    /**
     * هل المستخدم يملك هذا الحيوان؟
     */
    public function view(User $user, Animal $animal): bool
    {
        return $user->id === $animal->user_id;
    }

    public function update(User $user, Animal $animal): bool
    {
        return $user->id === $animal->user_id;
    }

    public function delete(User $user, Animal $animal): bool
    {
        return $user->id === $animal->user_id;
    }
}
