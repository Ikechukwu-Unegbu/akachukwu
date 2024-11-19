<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait GeneratesTransactionId
{
    protected static function bootGeneratesTransactionId()
    {
        static::creating(function ($model) {
            if ($model->vendor->name === 'VTPASS') {
                $model->transaction_id = self::generateVTPASSTransactionId();
            } else {
                $model->transaction_id = self::generateUniqueTransactionId();
            }
        });
    }

    private static function generateVTPASSTransactionId(): string
    {
        return Str::slug(date('YmdHi') . '-data-' . Str::random(10) . Str::random(4));
    }

    private static function generateUniqueTransactionId(): string
    {
        $vendorCode = 'VST';
        $transactionNumber = Auth::id();
        $timestamp = date('YmdHis');
        $randomDigits = Str::random(6);

        return "{$vendorCode}|{$transactionNumber}|{$timestamp}|{$randomDigits}";
    }
}