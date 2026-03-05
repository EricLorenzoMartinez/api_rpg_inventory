<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
        // Define the validation rules for updating an existing item
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:weapon,armor,consumable',
            'slot' => 'nullable|in:head,body,weapon',
            'power' => 'required|integer|min:0|max:100',
        ];
    }

    //--------------------
    // CUSTOM VALIDATION LOGIC
    //--------------------
    public function withValidator($validator)
    {
        // Apply additional business logic validation after standard rules
        $validator->after(function ($validator) {
            $type = $this->input('type');
            $slot = $this->input('slot');

            // Ensure consumables do not have an assigned equipment slot
            if ($type === 'consumable' && $slot !== null) {
                $validator->errors()->add('slot', 'Los consumibles no se pueden equipar (el slot debe ser nulo).');
            }
            
            // Ensure weapons are assigned to the correct weapon slot
            if ($type === 'weapon' && $slot !== 'weapon') {
                $validator->errors()->add('slot', 'Las armas deben ir equipadas en el slot "weapon".');
            }
            
            // Ensure armor pieces are assigned to valid equipment slots (head or body)
            if ($type === 'armor' && !in_array($slot, ['head', 'body'])) {
                $validator->errors()->add('slot', 'Las armaduras deben ir equipadas en "head" o "body".');
            }
        });
    }
}