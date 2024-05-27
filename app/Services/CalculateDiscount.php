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
}