<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryMovementRequest extends FormRequest
{
    /**
     * DETERMINE IF THE USER IS AUTHORIZED TO MAKE THIS REQUEST
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * GET THE VALIDATION RULES THAT APPLY TO THE REQUEST
     */
    public function rules(): array
    {
        return [
            'character_id' => 'required|exists:characters,id',
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:LOOT,EQUIP,UNEQUIP,DROP',
        ];
    }
}
