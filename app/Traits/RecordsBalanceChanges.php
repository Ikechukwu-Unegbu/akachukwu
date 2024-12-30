<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait RecordsBalanceChanges
{
    const STATUS_ACTIVE = 1;

    protected static function bootRecordsBalanceChanges()
    {
        static::creating(function ($model) {
            $user = auth()->user();
            if ($user) {
                $model->balance_before = $user->account_balance;
                $model->balance_after = $model->balance_before;
    
                if ($model->status === self::STATUS_ACTIVE && self::where('reference_id', $model->reference_id)->exists()) {
                    $model->balance_after = $model->balance_before + $model->amount;
                }
            }
        });

        static::updating(function ($model) {
            DB::transaction(function () use ($model) {
                $user = auth()->user();
                if ($user) {
                    $existingRecord = self::where('reference_id', $model->reference_id)
                        ->where('user_id', $user->id)
                        ->lockForUpdate()
                        ->first();
    
                    if ($model->status === self::STATUS_ACTIVE && $existingRecord) {
                        $model->balance_after = $existingRecord->balance_after + $model->amount;
                    }
                }
            });
        });
    }
}
