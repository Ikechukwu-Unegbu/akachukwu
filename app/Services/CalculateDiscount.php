<?php

namespace App\Services;

use App\Models\ResellerDiscount;

class CalculateDiscount
{
    public static function applyDiscount($amount, $type)
    {
        $discountPercentage = ResellerDiscount::whereType($type)->whereStatus(true)->first()?->discount ?? 0;

        if ($discountPercentage > 0) {
            $discountAmount = (int) (($amount * $discountPercentage) / 100);
            $amount -= $discountAmount;
        }

        return $amount;
    }

    public static function calculate(float $amount, float $discountRate): int
    {
        if ($discountRate == 0) {
            return (int) $amount;
        }
    
        if ($discountRate < 0 || $discountRate > 100) {
            throw new \InvalidArgumentException('Discount rate must be between 0 and 100.');
        }
    
        $discount = ($discountRate / 100) * $amount;
    
        return (int) ($amount - $discount);
    }
}