<?php

namespace App\Actions\Throttle;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ThrottleAction
{
     /**
     * Apply throttling logic for a specific action and user.
     *
     * @param string $actionName The unique name of the action (e.g., 'buyAirtime').
     * @param int $userId The ID of the user performing the action.
     * @param int $maxAttempts The maximum number of attempts allowed (default: 1).
     * @param int $decaySeconds Time window in seconds for throttling (default: 60 seconds).
     * @throws \Exception
     */
    public function execute(string $actionName, int $userId, int $maxAttempts = 1, int $decaySeconds = 60)
    {
        $key = $actionName . '_' . $userId;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            throw new \Exception('You are performing this action too frequently. Please wait before trying again.');
        }

        RateLimiter::hit($key, $decaySeconds);

        sleep(random_int(1, 4));
    }
}
