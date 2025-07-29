<?php

namespace App\Http\Requests\V1\Api;

use App\Services\Money\BasePalmPayService;
use Illuminate\Foundation\Http\FormRequest;

class QueryAccountNumberAPIRequest extends FormRequest
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
            'account_number' => ['required', 'numeric', 'digits:10', function ($attribute, $value, $fail) {
                if (!BasePalmPayService::isBankTransferAvailable()) {
                    $fail("Bank transfer is not available. Please try again later.");
                }
            }],
            'bank_code'      => ['required', 'exists:banks,code']
        ];
    }
}
