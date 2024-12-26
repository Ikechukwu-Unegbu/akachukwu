<?php

namespace App\Traits;

trait RecordsBalanceChanges
{
    protected static function bootRecordsBalanceChanges()
    {
        static::creating(function ($model) {
            $user = auth()->user();
            $model->balance_before = $user->account_balance;
            $model->balance_after = $model->balance_before;
            if ($model->status === 1 && self::where('reference_id', $model->reference_id)->exists()) {
                $model->balance_after = $model->balance_before + $model->amount;
            }
        });
        
        static::updating(function ($model) {
            $user = auth()->user();
            if ($model->status === 1 && self::where('reference_id', $model->reference_id)->where('user_id', $user->id)->exists()) {
                $model->balance_after = $model->balance_before + $model->amount;
            }
        });
    }
}
