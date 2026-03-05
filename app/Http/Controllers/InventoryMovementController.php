<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Item;
use App\Models\InventoryMovement;
use App\Http\Requests\StoreInventoryMovementRequest;
use App\Services\MongoLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InventoryMovementController extends Controller
{
    //--------------------
    // STORE A NEW MOVEMENT
    //--------------------
    public function store(StoreInventoryMovementRequest $request, MongoLogService $logService)
    {
        // Validate and retrieve the necessary models
        $validated = $request->validated();
        $character = Character::findOrFail($validated['character_id']);
        $item = Item::findOrFail($validated['item_id']);

        // Authorize the action through policies
        Gate::authorize('update', $character);

        // RPG Business Logic: Check movement type and requirements
        $type = $validated['type'];
        $inventoryBalance = $this->calculateItemBalance($character, $item->id);

        if ($type === 'EQUIP') {
            // Ensure the item exists in inventory and is equipable
            if ($inventoryBalance <= 0) {
                return response()->json(['error' => 'No tienes este ítem en el inventario para equipar.'], 400);
            }
            if (!$item->slot) {
                return response()->json(['error' => 'Este ítem no se puede equipar (consumible).'], 400);
            }

            // Verify if the specific equipment slot is already occupied
            $equipment = $this->getEquipmentData($character);
            if (isset($equipment[$item->slot])) {
                return response()->json(['error' => 'El slot ' . $item->slot . ' ya está ocupado.'], 400);
            }
        }
        elseif ($type === 'UNEQUIP') {
            // Verify if the item is actually equipped
            $equipment = $this->getEquipmentData($character);
            if (!isset($equipment[$item->slot]) || $equipment[$item->slot]->id !== $item->id) {
                return response()->json(['error' => 'No tienes este ítem equipado en este momento.'], 400);
            }
        }
        elseif ($type === 'DROP') {
            // Verify if there is at least one instance of the item to drop
            if ($inventoryBalance <= 0) {
                return response()->json(['error' => 'No tienes este ítem para tirar.'], 400);
            }
        }

        // Create the inventory movement record
        $movement = InventoryMovement::create([
            'character_id' => $character->id,
            'item_id' => $item->id,
            'type' => $type,
            'executed_at' => now(),
        ]);

        // Log the activity in the external service
        $logService->recordLog(
            action: 'inventory_movement_created',
            userId: $request->user()->id,
            metadata: [
                'movement_type' => $type,
                'item_name' => $item->name,
                'slot_affected' => $item->slot
            ],
            characterId: $character->id,
            itemId: $item->id
        );

        // Return the movement data with a success status
        return response()->json($movement, 201);
    }

    //--------------------
    // GET CHARACTER INVENTORY
    //--------------------
    public function inventory(Character $character)
    {
        // Check authorization to view character data
        Gate::authorize('view', $character);

        // Process movements to calculate current inventory state
        $movements = $character->inventoryMovements()->with('item')->orderBy('id', 'asc')->get();
        $inventory = [];

        foreach ($movements as $mov) {
            $item = $mov->item;
            $itemId = $item->id;

            if ($mov->type === 'LOOT') {
                $inventory[$itemId] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => $item->type,
                    'slot' => $item->slot,
                    'power' => $item->power,
                    'status' => 'unequipped'
                ];
            } elseif ($mov->type === 'EQUIP') {
                if (isset($inventory[$itemId])) {
                    $inventory[$itemId]['status'] = 'equipped';
                }
            } elseif ($mov->type === 'UNEQUIP') {
                if (isset($inventory[$itemId])) {
                    $inventory[$itemId]['status'] = 'unequipped';
                }
            } elseif ($mov->type === 'DROP') {
                unset($inventory[$itemId]);
            }
        }

        // Return the processed inventory list
        return response()->json(array_values($inventory));
    }

    //--------------------
    // GET CHARACTER EQUIPMENT
    //--------------------
    public function equipment(Character $character)
    {
        // Authorize and return the current equipment slots data
        Gate::authorize('view', $character);
        return response()->json($this->getEquipmentData($character));
    }

    //--------------------
    // PRIVATE HELPER FUNCTIONS
    //--------------------

    private function getEquipmentData(Character $character)
    {
        // Initialize the standard equipment structure
        $equipment = [
            'head' => null,
            'body' => null,
            'weapon' => null,
        ];

        // Process relevant movements chronologically to determine active equipment
        $movements = $character->inventoryMovements()->with('item')
            ->whereIn('type', ['EQUIP', 'UNEQUIP', 'DROP'])
            ->orderBy('executed_at', 'asc')
            ->get();

        foreach ($movements as $mov) {
            $item = $mov->item;
            if (!$item || !$item->slot) {
                continue; 
            }

            if ($mov->type === 'EQUIP') {
                $equipment[$item->slot] = $item;
            } elseif (in_array($mov->type, ['UNEQUIP', 'DROP'])) {
                if ($equipment[$item->slot]?->id === $item->id) {
                    $equipment[$item->slot] = null;
                }
            }
        }
        return $equipment;
    }

    private function calculateItemBalance(Character $character, $itemId)
    {
        // Calculate the total quantity of a specific item in the inventory
        $movements = $character->inventoryMovements()->where('item_id', $itemId)->get();
        $balance = 0;
        foreach ($movements as $mov) {
            if ($mov->type === 'LOOT' || $mov->type === 'UNEQUIP') $balance++;
            if ($mov->type === 'EQUIP' || $mov->type === 'DROP') $balance--;
        }
        return $balance;
    }
}