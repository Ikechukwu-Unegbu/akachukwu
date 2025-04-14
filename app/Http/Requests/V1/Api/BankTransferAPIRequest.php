<?php

namespace App\Http\Requests\V1\Api;

use App\Models\SiteSetting;
use App\Models\MoneyTransfer;
use App\Helpers\GeneralHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class BankTransferAPIRequest extends FormRequest
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
            'account_number' => ['required', 'numeric', 'digits:10'],
            'account_name'   => ['required', 'string'],
            'bank_code'      => ['required', 'exists:banks,code'],
            'amount' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:100',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    $settings = SiteSetting::first();

                    if ($value > $user->account_balance) {
                        $fail("The {$attribute} exceeds your available balance of ₦" . number_format($user->account_balance, 2));
                    }

                    if (!GeneralHelpers::minimumTransaction($value)) {
                        $fail("The minimum transfer amount is ₦" . number_format($settings->minimum_transfer, 2));
                    }

                    if (!GeneralHelpers::dailyTransactionLimit(MoneyTransfer::class, $value, $user->id)) {
                        $fail("You have exceeded your daily transaction limit.");
                    }
                }
            ],
            'remark' => 'nullable|string|min:5|max:50'
        ];
    }
}
