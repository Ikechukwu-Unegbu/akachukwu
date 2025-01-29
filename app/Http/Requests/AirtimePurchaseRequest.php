<?php

namespace App\Http\Requests;

use App\Models\SiteSetting;
use App\Services\AirtimeValidationService;
use Illuminate\Foundation\Http\FormRequest;

class AirtimePurchaseRequest extends FormRequest
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
            'network'       =>  ['required', 'exists:data_networks,network_id'],
            'amount'        =>  [
                'required', 
                'numeric', 
                'min:50',  
                function ($attribute, $value, $fail) {
                    $errorMessage = AirtimeValidationService::validateAirtimeAmount($value);
                    if ($errorMessage) {
                        $fail($errorMessage);
                    }
                }
            ],
            'phone_number'  =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/']
        ];
    }

    /**
     * Custom validation logic for checking account balance and availability of airtime purchase.
     */
    public function withValidator($validator)
    {
        $validator->addExtension('airtime_sales_status', function ($attribute, $value, $parameters, $validator) {
            $settings = SiteSetting::find(1);  // Assuming this retrieves the settings

            // Check if airtime sales are enabled
            if (!$settings->airtime_sales) {
                return false;  // Prevent validation if airtime sales is not enabled
            }

            return true;  // Validation is true if airtime sales are enabled
        });

        // Custom error message for when airtime sales is not enabled
        $validator->addReplacer('airtime_sales_status', function ($message, $attribute, $rule, $parameters) {
            return "Airtime purchase is currently unavailable.";  // Custom error message
        });

        // Add the custom validation rule to check the airtime sales status
        $validator->after(function ($validator) {
            $settings = SiteSetting::find(1);  // Assuming this retrieves the settings

            if (!$settings->airtime_sales) {
                // Add custom validation error for the entire form
                $validator->errors()->add('airtime_sales_status', 'Airtime purchase is currently unavailable.');
            }
        });
    }
}
