<?php

namespace App\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPinRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_pin'   =>  'required|numeric|digits:4',
            'pin'   =>  'required|numeric|digits:4',
            'pin_confirmation'  =>  'required|same:pin'
        ];
    }
}
