<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    /**
     * DETERMINE IF THE USER IS AUTHORIZED TO MAKE THIS REQUEST
     */
    public function authorize(): bool
    {
        // Allow the user to proceed with this request
        return true;
    }

    /**
     * GET THE VALIDATION RULES THAT APPLY TO THE REQUEST
     */
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

    /**
     * CONFIGURE THE VALIDATOR INSTANCE TO ADD CUSTOM VALIDATION LOGIC
     */
    public function withValidator($validator)
    {
        // Apply additional business logic validation after standard rules
        $validator->after(function ($validator) {
            $type = $this->input('type');
            $slot = $this->input('slot');

            // Ensure consumables do not have an assigned equipment slot
            if ($type === 'consumable' && $slot !== null) {
                $validator->errors()->add('slot', 'Consumables can not be equipped (slot must be null).');
            }

            // Ensure weapons are assigned to the correct weapon slot
            if ($type === 'weapon' && $slot !== 'weapon') {
                $validator->errors()->add('slot', 'Weapons must be equipped in the "weapon" slot.');
            }

            // Ensure armor pieces are assigned to valid equipment slots (head or body)
            if ($type === 'armor' && !in_array($slot, ['head', 'body'])) {
                $validator->errors()->add('slot', 'Armors must be equipped in the "head" or "body" slot.');
            }
        });
    }
}
