<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a single Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create 3 standard Player users
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
