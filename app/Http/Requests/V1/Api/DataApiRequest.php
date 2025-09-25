<?php

namespace App\Http\Requests\V1\Api;

use App\Models\Data\DataPlan;
use App\Services\Account\AccountBalanceService;
use App\Services\Blacklist\CheckBlacklist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class DataApiRequest extends FormRequest
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
            'wallet' => 'nullable|in:base_wallet,crypto_wallet',
            'network_id'        =>  'required|integer',
            'data_type_id'      =>  'required|integer',
            'plan_id'           =>  'required|integer',
            'phone_number'      =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
        ];
    }

    public function withValidator($validator)
    {

        $validator->after(function ($validator) {
            if ($this->has('rate_limit_error')) {
                $validator->errors()->add('rate_limit', 'Wait a minute. Your last transaction is still processing.');
            }
        });

        $validator->after(function ($validator) {
            if ($this->has('account')) {
                $validator->errors()->add('account', "There a problem with your account. Contact support.");
            }
        });
        $validator->after(function ($validator) {
            if ($this->has('kyc')) {
                $validator->errors()->add('kcy', 'Please complete your kyc first');
            }
        });
        $validator->after(function ($validator) {
            $user = Auth::user();

            $plan = DataPlan::find($this->plan_id); // Fetch the plan using submitted plan_id
            if (!$plan) {
                $validator->errors()->add('plan_id', 'Invalid plan selected.');
                return;
            }

            $accountBalanceService = new AccountBalanceService($user);

            if (!$accountBalanceService->verifyAccountBalance($plan->amount)) {
                $validator->errors()->add('plan_id', 'Insufficient balance for this transaction.');
            }
        });
    }

    protected function prepareForValidation()
    {
        $rateLimitKey = 'data-api-submit-' . Auth::id();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 2)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            $this->merge([
                'rate_limit_error' => "Wait a moment. Last transaction is still processing.",
            ]);
        } else {
            
            RateLimiter::hit($rateLimitKey, 30);
        }

        $user = Auth::user();
        if( CheckBlacklist::checkIfUserIsBlacklisted()){
            $this->merge([
                'account' => "There a problem with your account. Contact support.",
            ]);
        }
        if(!$user->hasCompletedKYC()){
            $this->merge([
                'kyc' => "Please complete your kyc first.",
            ]);
        }
    }

}
