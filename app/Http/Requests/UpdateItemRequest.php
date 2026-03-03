<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
     * Configure the validator instance to add custom validation logic after the default rules are applied.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('type');
            $slot = $this->input('slot');

            if ($type === 'consumable' && $slot !== null) {
                $validator->errors()->add('slot', 'Los consumibles no se pueden equipar (el slot debe ser nulo).');
            }
            if ($type === 'weapon' && $slot !== 'weapon') {
                $validator->errors()->add('slot', 'Las armas deben ir equipadas en el slot "weapon".');
            }
            if ($type === 'armor' && !in_array($slot, ['head', 'body'])) {
                $validator->errors()->add('slot', 'Las armaduras deben ir equipadas en "head" o "body".');
            }
        });
    }
}
