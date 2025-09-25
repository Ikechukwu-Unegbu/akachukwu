<?php 
namespace App\Http\Requests\V1\Api;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\Account\AccountBalanceService;
use App\Services\Blacklist\CheckBlacklist;

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
            'network_id'    => 'required|integer',
            'amount'        => [
                'required',
                'numeric',
                'min:50',
                'check_balance', // Custom validation rule
                function ($attribute, $value, $fail) {
                    $settings = SiteSetting::find(1);
                    if (!$settings->airtime_sales) {
                        $fail('Airtime purchase is currently unavailable.');
                    }
                }
            ],
            'phone_number'  => ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
           'wallet' => 'nullable|in:base_wallet,crypto_wallet',

        ];
    }

    /**
     * Custom validation logic.
     */
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
        // Register the custom validation rule for checking balance
        $this->registerCheckBalanceRule($validator);

    }

       /**
     * Prepare the data for validation.
     *
     * This is where the rate-limiter logic is enforced.
     */
 

    protected function prepareForValidation()
    {
        $rateLimitKey = 'airtime-submit-' . Auth::id();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
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

    /**
     * Register the custom rule for checking balance.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    protected function registerCheckBalanceRule($validator)
    {
        $validator->addExtension('check_balance', function ($attribute, $value, $parameters, $validator) {
            $user = Auth::user();
            $accountBalanceService = new AccountBalanceService($user);

            return $accountBalanceService->verifyAccountBalance($value);
        });

        $validator->addReplacer('check_balance', function ($message, $attribute, $rule, $parameters) {
            return "Insufficient balance for this transaction."; // Custom error message
        });
    }


}
