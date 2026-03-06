<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCharacterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** Allow the user to proceed with this request */
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        /** Define the validation rules for creating a new character */
        return [
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
        ];
    }
}