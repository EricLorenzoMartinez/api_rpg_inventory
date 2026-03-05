<?php

namespace App\Policies;

use App\Models\Character;
use App\Models\User;

class CharacterPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        // El admin puede acceder a todo
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    public function view(User $user, Character $character): bool
    {
        // Un jugador solo puede acceder a sus personajes
        return $user->id === $character->user_id || $user->role === 'admin';
    }

    public function update(User $user, Character $character): bool
    {
        return $user->id === $character->user_id;
    }

    public function delete(User $user, Character $character): bool
    {
        return $user->id === $character->user_id;
    }
}
