<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait PaymentStatusTrait
{
    protected const STATUS_SUCCESS = 'successful';
    protected const STATUS_PROCESSING = 'processing';
    protected const STATUS_FAILED = 'failed';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->status = 0;
            $model->api_status = self::STATUS_FAILED;            
        });
    }

    public function failed()
    {
        $this->update([
            'status' => 0,
            'api_status' => self::STATUS_FAILED,
        ]);
    }

    public function success()
    {
        $this->update([
            'status' => 1,
            'api_status' => self::STATUS_SUCCESS,
        ]);
    }
}