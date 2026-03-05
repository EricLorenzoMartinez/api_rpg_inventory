<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCharacterRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'level' => 'sometimes|integer|min:1',
        ];
    }
}
