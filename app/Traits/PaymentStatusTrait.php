<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait PaymentStatusTrait
{
    protected const STATUS_SUCCESS = 'success';
    protected const STATUS_PROCESSING = 'processing';
    protected const STATUS_FAILED = 'failed';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = Auth::id();
            $model->status = 0;
            $model->api_status = self::STATUS_PROCESSING;            
        });
    }

    public function processing()
    {
        $this->status = 0;
        $this->api_status = 'processing';
        $this->save();
    }

    public function success()
    {
        $this->status = 1;
        $this->api_status = 'successful';
        $this->save();
    }
}