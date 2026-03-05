<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Character;
use App\Models\Item;
use App\Models\InventoryMovement;
use App\Services\MongoLogService;

class InventoryMovementSeeder extends Seeder
{
    public function run(MongoLogService $logService): void
    {
        $characters = Character::all();
        $weapons = Item::where('type', 'weapon')->get();
        $heads = Item::where('slot', 'head')->get();
        $bodies = Item::where('slot', 'body')->get();
        $consumables = Item::where('type', 'consumable')->get();

        if ($characters->count() < 6 || $weapons->isEmpty() || $consumables->isEmpty()) {
            $this->command->warn('Faltan personajes o ítems. Revisa el DatabaseSeeder.');
            return;
        }

        // Función "helper" para crear el movimiento en MySQL y el log en Mongo de un solo golpe
        $makeMovement = function ($char, $item, $type) use ($logService) {
            InventoryMovement::create([
                'character_id' => $char->id,
                'item_id' => $item->id,
                'type' => $type,
                'executed_at' => now(),
            ]);

            $logService->recordLog(
                action: 'inventory_movement_created',
                userId: $char->user_id,
                metadata: ['movement_type' => $type, 'item_name' => $item->name, 'slot' => $item->slot],
                characterId: $char->id,
                itemId: $item->id
            );
        };

        // --- CASOS OBLIGATORIOS ---
        $char1 = $characters[0]; // Caso 1: Full Equipado
        $char2 = $characters[1]; // Caso 2: Inventario con cosas, pero sin equipar
        $char3 = $characters[2]; // Caso 3: Inventario vacío

        // 1. Full Equipado (Arma + Cabeza + Cuerpo) -> 3 LOOT, 3 EQUIP
        $makeMovement($char1, $weapons->first(), 'LOOT');
        $makeMovement($char1, $heads->first(), 'LOOT');
        $makeMovement($char1, $bodies->first(), 'LOOT');
        $makeMovement($char1, $weapons->first(), 'EQUIP');
        $makeMovement($char1, $heads->first(), 'EQUIP');
        $makeMovement($char1, $bodies->first(), 'EQUIP');

        // 2. Inventario con cosas, sin equipar -> 2 LOOT, 1 EQUIP, 1 UNEQUIP (lo deja en la mochila)
        $makeMovement($char2, $weapons->last(), 'LOOT');
        $makeMovement($char2, $consumables->first(), 'LOOT');
        $makeMovement($char2, $weapons->last(), 'EQUIP');
        $makeMovement($char2, $weapons->last(), 'UNEQUIP');

        // 3. Inventario vacío -> Coge dos cosas y las tira (2 LOOT, 2 DROP)
        $itemA = $consumables->last();
        $itemB = $weapons->random();
        $makeMovement($char3, $itemA, 'LOOT');
        $makeMovement($char3, $itemB, 'LOOT');
        $makeMovement($char3, $itemA, 'DROP');
        $makeMovement($char3, $itemB, 'DROP');

        // --- RELLENO MATEMÁTICO PARA LLEGAR A LOS 60 ---
        // Hasta aquí llevamos: 7 L, 4 E, 1 U, 2 D (Total: 14)
        // Nos faltan: 23 L, 6 E, 9 U, 8 D para clavar los 60 exactos
        
        $otherChars = [$characters[3], $characters[4], $characters[5]];

        for ($i = 0; $i < 23; $i++) { $makeMovement($otherChars[$i % 3], $consumables->random(), 'LOOT'); }
        for ($i = 0; $i < 6; $i++) { $makeMovement($otherChars[$i % 3], $weapons->random(), 'EQUIP'); }
        for ($i = 0; $i < 9; $i++) { $makeMovement($otherChars[$i % 3], $weapons->random(), 'UNEQUIP'); }
        for ($i = 0; $i < 8; $i++) { $makeMovement($otherChars[$i % 3], $consumables->random(), 'DROP'); }
    }
}