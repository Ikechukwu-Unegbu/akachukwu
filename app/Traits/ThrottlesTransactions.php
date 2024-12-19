<?php

namespace App\Traits;

use App\Events\RateLimitExceeded;
use Illuminate\Support\Facades\Auth;
use App\Actions\Throttle\ThrottleAction;

trait ThrottlesTransactions
{
    public static function bootThrottlesTransactions()
    {
        static::creating(function ($model) {
        
            $actionName = property_exists($model, 'throttleActionName')
                ? $model->throttleActionName
                : 'purchase' . class_basename($model);

            try {
                $throttler = new ThrottleAction();
                $throttler->execute($actionName, Auth::id());
            } catch (\Exception $e) {
                event(new RateLimitExceeded($e->getMessage()));
                return redirect()->to(url()->previous());
            }
        });
    }
}
