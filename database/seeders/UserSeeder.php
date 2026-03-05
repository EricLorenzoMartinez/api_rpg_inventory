<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos 1 Admin [cite: 158]
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Creamos 3 Players [cite: 159]
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Jugador $i",
                'email' => "player$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'player',
            ]);
        }
    }
}