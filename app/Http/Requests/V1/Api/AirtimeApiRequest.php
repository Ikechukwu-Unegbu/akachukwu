<?php

namespace App\Http\Requests\V1\Api;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\Account\AccountBalanceService;

class AirtimeApiRequest extends FormRequest
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
            'network_id'    =>  'required|integer',
            // 'amount'        =>  'required|numeric|min:50|check_balance',
            'amount'        =>  [
                'required', 
                'numeric', 
                'min:50',
                'check_balance', 
                function ($attribute, $value, $fail) {
                    $settings = SiteSetting::find(1);
                    if (!$settings->airtime_sales) {
                        return $fail('Airtime purchase is currently unavailable.');
                    }
                }
            ],
            'phone_number'  =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
        ];
    }

     /**
     * Custom validation logic for checking account balance.
     */
    public function withValidator($validator)
    {
        $validator->addExtension('check_balance', function($attribute, $value, $parameters, $validator) {
            $user = Auth::user(); 
            $accountBalanceService = new AccountBalanceService($user); 

            return $accountBalanceService->verifyAccountBalance($value);
        });

        $validator->addReplacer('check_balance', function($message, $attribute, $rule, $parameters) {
            return "Insufficient balance for this transaction.";  // Custom error message
        });
    }
}
