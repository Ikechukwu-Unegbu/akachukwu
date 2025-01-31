<?php

namespace App\Rules;

use Closure;
use Carbon\Carbon;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility\AirtimeTransaction;
use Illuminate\Contracts\Validation\ValidationRule;

class AirtimeValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userId = Auth::id();
        $settings = SiteSetting::find(1);
        if (!$settings || !$settings->airtime_sales) {
            $fail('Airtime purchase is currently unavailable.');
            return;
        }

        $dailyLimit = $settings->airtime_limit;

        $totalSpentToday = AirtimeTransaction::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        if (($totalSpentToday + $value) > $dailyLimit) {
            $fail("You have exceeded your daily airtime transaction limit of ₦{$dailyLimit}.");
            return;
        }
    }
}
