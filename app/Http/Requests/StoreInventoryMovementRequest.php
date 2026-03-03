<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'character_id' => 'required|exists:characters,id',
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:LOOT,EQUIP,UNEQUIP,DROP',
        ];
    }
}