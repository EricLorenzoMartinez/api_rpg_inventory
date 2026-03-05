<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Item;
use App\Models\InventoryMovement;
use App\Http\Requests\StoreInventoryMovementRequest;
use App\Services\MongoLogService; // <-- ¡Faltaba importar esto!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InventoryMovementController extends Controller
{
    // ¡AÑADIDO!: Inyección de MongoLogService en los parámetros
    public function store(StoreInventoryMovementRequest $request, MongoLogService $logService)
    {
        $validated = $request->validated();
        $character = Character::findOrFail($validated['character_id']);
        $item = Item::findOrFail($validated['item_id']);

        // 1. Validar Ownership (Policy de Character)
        Gate::authorize('update', $character);

        // 2. Reglas RPG (Lógica de negocio)
        $type = $validated['type'];

        // Calcular cuántos ítems de este tipo tiene en la mochila (LOOT + UNEQUIP - EQUIP - DROP)
        $inventoryBalance = $this->calculateItemBalance($character, $item->id);

        if ($type === 'EQUIP') {
            if ($inventoryBalance <= 0) {
                return response()->json(['error' => 'No tienes este ítem en el inventario para equipar.'], 400);
            }
            if (!$item->slot) {
                return response()->json(['error' => 'Este ítem no se puede equipar (consumible).'], 400);
            }

            // Comprobar si el slot ya está ocupado
            $equipment = $this->getEquipmentData($character);
            if (isset($equipment[$item->slot])) {
                return response()->json(['error' => 'El slot ' . $item->slot . ' ya está ocupado.'], 400);
            }
        }
        elseif ($type === 'UNEQUIP') {
            $equipment = $this->getEquipmentData($character);
            if (!isset($equipment[$item->slot]) || $equipment[$item->slot]->id !== $item->id) {
                return response()->json(['error' => 'No tienes este ítem equipado en este momento.'], 400);
            }
        }
        elseif ($type === 'DROP') {
            if ($inventoryBalance <= 0) {
                return response()->json(['error' => 'No tienes este ítem para tirar.'], 400);
            }
        }

        // 3. Guardar el movimiento
        $movement = InventoryMovement::create([
            'character_id' => $character->id,
            'item_id' => $item->id,
            'type' => $type,
            'executed_at' => now(),
        ]);

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

        return response()->json($movement, 201);
    }

    public function inventory(Character $character)
    {
        Gate::authorize('view', $character);

        // MEJORADO: Lógica secuencial para mantener el ítem visible aunque se equipe,
        // devolviendo los datos requeridos (id, name, type, slot, power)
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

        return response()->json(array_values($inventory));
    }

    public function equipment(Character $character)
    {
        Gate::authorize('view', $character);
        return response()->json($this->getEquipmentData($character));
    }

    // --- Funciones Auxiliares Privadas ---

    private function getEquipmentData(Character $character)
    {
        $movements = $character->inventoryMovements()->with('item')
            ->whereIn('type', ['EQUIP', 'UNEQUIP'])->orderBy('id', 'asc')->get();
        $equipment = [];

        foreach ($movements as $mov) {
            $slot = $mov->item->slot;
            if ($mov->type === 'EQUIP') {
                $equipment[$slot] = $mov->item;
            } elseif ($mov->type === 'UNEQUIP') {
                unset($equipment[$slot]);
            }
        }
        return $equipment;
    }

    private function calculateItemBalance(Character $character, $itemId)
    {
        $movements = $character->inventoryMovements()->where('item_id', $itemId)->get();
        $balance = 0;
        foreach ($movements as $mov) {
            if ($mov->type === 'LOOT' || $mov->type === 'UNEQUIP') $balance++;
            if ($mov->type === 'EQUIP' || $mov->type === 'DROP') $balance--;
        }
        return $balance;
    }
}