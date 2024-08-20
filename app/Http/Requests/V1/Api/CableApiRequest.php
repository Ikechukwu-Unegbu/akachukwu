<?php

namespace App\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;

class CableApiRequest extends FormRequest
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
            'cable_id'         =>  'required',
            'iuc_number'       =>  'required|numeric',
            'cable_plan_id'    =>  'required',
            'card_owner'       =>  'required|string'
        ];
    }
}
