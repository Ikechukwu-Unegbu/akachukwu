<?php

namespace App\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;

class VirtualAccountRequest extends FormRequest
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
            // 'bvn' => 'nullable|numeric|digits:11|required_without:nin|unique:users,bvn',
            // 'nin' => 'nullable|numeric|digits:11|required_without:bvn|unique:users,nin',
            'bvn' => 'nullable|numeric|digits:11|required_without:nin',
            'nin' => 'nullable|numeric|digits:11|required_without:bvn',
            'account_number' => 'required_with:bvn|numeric|digits:10',
            'bank_code' => 'required_with:bvn'
        ];
    }
}
