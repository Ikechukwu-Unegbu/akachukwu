<?php

namespace App\Http\Requests\V1\API;

use Illuminate\Foundation\Http\FormRequest;

class ElectricityApiRequest extends FormRequest
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
            'amount'            =>  'required|numeric',
            'meter_number'      =>  'required|numeric',
            'disco_id'          =>  'required',
            'meter_type'        =>  'required|integer',
            'owner_name'        =>  'required|string',
            'phone_number'      =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
            'owner_address'     =>  'required|string',
        ];
    }
}
