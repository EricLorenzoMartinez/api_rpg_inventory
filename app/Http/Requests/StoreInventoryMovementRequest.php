<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryMovementRequest extends FormRequest
{
    //--------------------
    // AUTHORIZE THE REQUEST
    //--------------------
    public function authorize(): bool
    {
        // Allow the user to proceed with this request
        return true;
    }

    //--------------------
    // GET THE VALIDATION RULES
    //--------------------
    public function rules(): array
    {
        // Define the validation rules for a new inventory movement
        return [
            'character_id' => 'required|exists:characters,id',
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:LOOT,EQUIP,UNEQUIP,DROP',
        ];
    }
}