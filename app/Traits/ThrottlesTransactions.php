<?php

namespace App\Traits;

use App\Actions\Throttle\ThrottleAction;
use Illuminate\Support\Facades\Auth;

trait ThrottlesTransactions
{
    public static function bootThrottlesTransactions()
    {
        static::creating(function ($model) {
        
            $actionName = property_exists($model, 'throttleActionName')
                ? $model->throttleActionName
                : 'purchase' . class_basename($model);

            $throttler = new ThrottleAction();
            $throttler->execute($actionName, Auth::id());
        });
    }
}
