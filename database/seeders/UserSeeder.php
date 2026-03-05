<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    //--------------------
    // RUN THE SEEDER
    //--------------------
    public function run(): void
    {
        // 1. Create a single Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create 3 standard Player users
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