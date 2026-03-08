<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\User;

class CharacterPolicy
{
    /**
     * Global policy override.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Grant full access to users with the admin role
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Character $character): bool
    {
        // Players can only access their own characters unless they are admins
        return $user->id === $character->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Character $character): bool
    {
        // Verify if the character belongs to the authenticated user
        return $user->id === $character->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Character $character): bool
    {
        // Only the owner is allowed to delete the character
        return $user->id === $character->user_id;
    }
}
