<?php 
namespace App\Http\Requests\V1\Api;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
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
        // Register the custom validation rule for checking balance
        $this->registerCheckBalanceRule($validator);


        // Add custom rule for blacklist and KYC check
        $validator->after(function ($validator) {
            $user = Auth::user();

            if ($user->is_blacklisted) {
                $validator->errors()->add('user_status', 'You are blacklisted and cannot perform this transaction.');
            }

            if (!$user->hasCompletedKYC()) {
                $validator->errors()->add('kyc_status', 'You must complete KYC verification to perform this transaction.');
            }
        });
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

            // Merge the rate limit error into the request for validation handling
            $this->merge([
                'rate_limit_error' => "Wait a moment. Last transaction is still processing. Try again in {$seconds} seconds.",
            ]);
        } else {
            // Record a new attempt
            RateLimiter::hit($rateLimitKey, 60); // Limit: 1 attempt per 60 seconds
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
