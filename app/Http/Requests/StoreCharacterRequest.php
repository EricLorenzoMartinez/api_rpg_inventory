<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCharacterRequest extends FormRequest
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
        // Define the validation rules for creating a new character
        return [
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
        ];
    }
}