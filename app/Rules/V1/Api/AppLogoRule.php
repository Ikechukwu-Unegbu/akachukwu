<?php

namespace App\Rules\V1\Api;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AppLogoRule implements ValidationRule
{

    protected $validColumns = ['mtn_logo', 'airtel_logo', 'glo_logo', '9mobile_logo', 'app_logo'];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value, $this->validColumns)) {
            $fail('The selected :attribute is invalid.');
        }
    }
    
}
