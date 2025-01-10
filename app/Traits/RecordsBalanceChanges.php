<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\DB;

trait RecordsBalanceChanges
{
    protected const STATUS_SUCCESS = 'successful';
    protected const STATUS_PROCESSING = 'processing';
    protected const STATUS_PENDING = 'pending';
    protected const STATUS_REFUND = 'refunded';
    protected const STATUS_FAILED = 'failed';

    protected static function bootRecordsBalanceChanges()
    {
        static::creating(function ($model) {
            DB::transaction(function () use ($model) {
                $user = User::where('id', $model->user_id)->lockForUpdate()->firstOrFail();
                $model->balance_before = $user->account_balance;
                $model->balance_after = $model->balance_before;
            
                $statusField = $model->getStatusField();
                $field = ($statusField === 'vendor_status') ? 'transaction_id' : 'reference_id';
            
                if ($model->$statusField === self::STATUS_SUCCESS && self::where($field, $model->$field)->exists()) {
                    $model->balance_after = $user->account_balance;
                }
            });
        });
        

        static::updating(function ($model) {
            DB::transaction(function () use ($model) {
                $user = User::where('id', $model->user_id)->lockForUpdate()->firstOrFail();
                $statusField = $model->getStatusField();
                $field = ($statusField === 'vendor_status') ? 'transaction_id' : 'reference_id';
        
                $existingRecord = self::where($field, $model->$field)
                    ->where('user_id', $user->id)
                    ->lockForUpdate()
                    ->first();
        
                if ($existingRecord) {
                    $model->balance_after = $user->account_balance;
                }
            });
        });
        
    }

    /**
     * Get the status field name dynamically.
     *
     * @return string
     */
    protected function getStatusField()
    {
        return property_exists($this, 'statusField') ? $this->statusField : 'vendor_status';
    }
}
