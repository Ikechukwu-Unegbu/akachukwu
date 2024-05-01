<?php

namespace App\Http\Requests\V1\API;

use Illuminate\Foundation\Http\FormRequest;

class MeterApiRequest extends FormRequest
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
            'meter_number'  =>  'required|numeric',
            'disco_id'      =>  'required|integer',
            'meter_type'    =>  'required|integer'
        ];
    }
}
