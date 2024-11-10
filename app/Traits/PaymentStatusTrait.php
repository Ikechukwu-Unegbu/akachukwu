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
        $this->status = 0;
        $this->api_status = self::STATUS_FAILED;
        $this->save();
    }

    public function success()
    {
        $this->status = 1;
        $this->api_status = self::STATUS_SUCCESS;
        $this->save();
    }
}