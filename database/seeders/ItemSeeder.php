<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // 6 Armas (slot: 'weapon') [cite: 167]
        for ($i = 1; $i <= 6; $i++) {
            Item::create([
                'name' => "Espada Nivel $i",
                'type' => 'weapon',
                'slot' => 'weapon',
                'power' => rand(10, 100),
            ]);
        }

        // 6 Armaduras: 3 Cascos ('head') y 3 Pecheras ('body') [cite: 168]
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

        // 6 Consumibles (slot: null) [cite: 169]
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