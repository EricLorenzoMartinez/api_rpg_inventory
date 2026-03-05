<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    //--------------------
    // RUN THE SEEDER
    //--------------------
    public function run(): void
    {
        // Generate 6 Weapons (assigned to the 'weapon' slot)
        for ($i = 1; $i <= 6; $i++) {
            Item::create([
                'name' => "Espada Nivel $i",
                'type' => 'weapon',
                'slot' => 'weapon',
                'power' => rand(10, 100),
            ]);
        }

        // Generate 6 Armor pieces: 3 Helmets ('head') and 3 Chestplates ('body')
        for ($i = 1; $i <= 3; $i++) {
            Item::create([
                'name' => "Casco de Hierro $i",
                'type' => 'armor',
                'slot' => 'head',
                'power' => rand(10, 50),
            ]);
        }
        
        for ($i = 1; $i <= 3; $i++) {
            Item::create([
                'name' => "Pechera de Acero $i",
                'type' => 'armor',
                'slot' => 'body',
                'power' => rand(20, 80),
            ]);
        }

        // Generate 6 Consumables (these do not have an equipment slot)
        for ($i = 1; $i <= 6; $i++) {
            Item::create([
                'name' => "Poción de Curación $i",
                'type' => 'consumable',
                'slot' => null,
                'power' => rand(5, 30),
            ]);
        }
    }
}