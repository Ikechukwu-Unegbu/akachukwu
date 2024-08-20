<?php

namespace App\Http\Requests\V1\Api;

use App\Models\SiteSetting;
use App\Rules\V1\Api\AppLogoRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class AppLogoRequest extends FormRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       
        return [
            'logo' => ['required', 'string'],
        ];
      
    }

    protected function failedValidation(ValidatorContract $validator)
    {
        $errors = $validator->errors()->toArray();
        $response = [
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $errors,
        ];

        throw new \Illuminate\Validation\ValidationException($validator, response()->json($response, 422));
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $column = $this->input('logo');

            // Check if the column exists in the 'site_settings' table
            if (!Schema::hasColumn('site_settings', $column)) {
                $validator->errors()->add('logo', 'Invalid column specified.');
                return;
            }

            $siteSetting = SiteSetting::select($column)->first();

            if (!$siteSetting) {
                $validator->errors()->add('logo', 'Site asset not found.');
            }
        });
    }

}
