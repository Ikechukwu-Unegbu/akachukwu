<?php

namespace App\Http\Requests\MoneyTransfer;

use Illuminate\Foundation\Http\FormRequest;

class InitiateTransferRequest extends FormRequest
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
            'transfer_amount' => [
                'required',
                'numeric',
                'min:50',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    if ($value > $user->account_balance) {
                        $fail("The {$attribute} exceeds your available balance of ₦" . number_format($user->account_balance, 2));
                    }
                }
            ],
            'remark' => 'nullable|string|max:50'
        ];
    }

    public function messages()
    {
        return [
            'transfer_amount.required' => 'Please enter an amount.',
            'transfer_amount.numeric'  => 'The amount must be a number.',
            'transfer_amount.min'      => 'The minimum transfer amount is ₦50.',
            'remark.max'      => 'The remark must not exceed 50 characters.'
        ];
    }
}
