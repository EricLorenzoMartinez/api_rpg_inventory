<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCharacterRequest extends FormRequest
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
        // Define the validation rules for updating an existing character
        return [
            'name' => 'sometimes|string|max:255',
            'level' => 'sometimes|integer|min:1',
        ];
    }
}