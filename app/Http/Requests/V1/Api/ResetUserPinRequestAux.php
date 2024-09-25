<?php

namespace App\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;

class ResetUserPinRequestAux extends FormRequest
{
  

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pin' => ['required', 'integer', 'digits:4'],
            'confirm_pin' => ['required', 'integer', 'digits:4', 'same:pin'],
        ];
        
    }
}
