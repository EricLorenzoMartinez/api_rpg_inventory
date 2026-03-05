<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,     // Hecho por tu compañero
            ItemSeeder::class,     // Hecho por tu compañero
            CharacterSeeder::class, // ¡El tuyo!
            InventoryMovementSeeder::class, // ¡El tuyo!
        ]);
    }
}
