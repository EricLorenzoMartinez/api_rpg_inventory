<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\User;

class CharacterPolicy
{
    //--------------------
    // GLOBAL POLICY OVERRIDE
    //--------------------
    public function before(User $user, string $ability): bool|null
    {
        // Grant full access to users with the admin role
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    //--------------------
    // VIEW AUTHORIZATION
    //--------------------
    public function view(User $user, Character $character): bool
    {
        // Players can only access their own characters unless they are admins
        return $user->id === $character->user_id || $user->role === 'admin';
    }

    //--------------------
    // UPDATE AUTHORIZATION
    //--------------------
    public function update(User $user, Character $character): bool
    {
        // Verify if the character belongs to the authenticated user
        return $user->id === $character->user_id;
    }

    //--------------------
    // DELETE AUTHORIZATION
    //--------------------
    public function delete(User $user, Character $character): bool
    {
        // Only the owner is allowed to delete the character
        return $user->id === $character->user_id;
    }
}