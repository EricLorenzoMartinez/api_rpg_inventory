<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryMovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
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
