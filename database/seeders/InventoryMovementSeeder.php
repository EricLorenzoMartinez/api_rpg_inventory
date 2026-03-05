<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Character;
use App\Models\Item;
use App\Models\InventoryMovement;
use App\Services\MongoLogService;

class InventoryMovementSeeder extends Seeder
{
    //--------------------
    // RUN THE SEEDER
    //--------------------
    public function run(MongoLogService $logService): void
    {
        // Retrieve necessary data for seeding movements
        $characters = Character::all();
        $weapons = Item::where('type', 'weapon')->get();
        $heads = Item::where('slot', 'head')->get();
        $bodies = Item::where('slot', 'body')->get();
        $consumables = Item::where('type', 'consumable')->get();

        // Ensure prerequisite data exists before proceeding
        if ($characters->count() < 6 || $weapons->isEmpty() || $consumables->isEmpty()) {
            $this->command->warn('Faltan personajes o ítems. Revisa el DatabaseSeeder.');
            return;
        }

        // Helper function to create both MySQL movement and MongoDB log entries simultaneously
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

        //--------------------
        // MANDATORY TEST CASES
        //--------------------
        
        $char1 = $characters[0]; // Case 1: Fully Equipped
        $char2 = $characters[1]; // Case 2: Items in inventory but unequipped
        $char3 = $characters[2]; // Case 3: Empty inventory

        // 1. Fully Equipped (Weapon + Head + Body)
        $makeMovement($char1, $weapons->first(), 'LOOT');
        $makeMovement($char1, $heads->first(), 'LOOT');
        $makeMovement($char1, $bodies->first(), 'LOOT');
        $makeMovement($char1, $weapons->first(), 'EQUIP');
        $makeMovement($char1, $heads->first(), 'EQUIP');
        $makeMovement($char1, $bodies->first(), 'EQUIP');

        // 2. Inventory with items, unequipped state
        $makeMovement($char2, $weapons->last(), 'LOOT');
        $makeMovement($char2, $consumables->first(), 'LOOT');
        $makeMovement($char2, $weapons->last(), 'EQUIP');
        $makeMovement($char2, $weapons->last(), 'UNEQUIP');

        // 3. Empty inventory (Loot then Drop)
        $itemA = $consumables->last();
        $itemB = $weapons->random();
        $makeMovement($char3, $itemA, 'LOOT');
        $makeMovement($char3, $itemB, 'LOOT');
        $makeMovement($char3, $itemA, 'DROP');
        $makeMovement($char3, $itemB, 'DROP');

        //--------------------
        // MATHEMATICAL FILLER
        //--------------------
        
        // Populate additional movements to reach exactly 60 records
        $otherChars = [$characters[3], $characters[4], $characters[5]];

        for ($i = 0; $i < 23; $i++) { $makeMovement($otherChars[$i % 3], $consumables->random(), 'LOOT'); }
        for ($i = 0; $i < 6; $i++) { $makeMovement($otherChars[$i % 3], $weapons->random(), 'EQUIP'); }
        for ($i = 0; $i < 9; $i++) { $makeMovement($otherChars[$i % 3], $weapons->random(), 'UNEQUIP'); }
        for ($i = 0; $i < 8; $i++) { $makeMovement($otherChars[$i % 3], $consumables->random(), 'DROP'); }
    }
}