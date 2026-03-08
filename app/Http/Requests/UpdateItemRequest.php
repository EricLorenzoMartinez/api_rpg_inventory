<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => 'required|in:weapon,armor,consumable',
            'slot' => 'nullable|in:head,body,weapon',
            'power' => 'required|integer|min:0|max:100',
        ];
    }

    /**
     * Configure the validator instance to add custom validation logic.
     */
    public function withValidator($validator)
    {
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
