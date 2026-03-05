<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCharacterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
        ];
    }
}
