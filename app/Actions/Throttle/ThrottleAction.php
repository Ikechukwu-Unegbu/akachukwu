<?php

namespace App\Actions\Throttle;

use App\Events\RateLimitExceeded;
use Illuminate\Support\Facades\RateLimiter;

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
            throw new \Exception('Your last transaction is still Processing. Please wait before trying again.');
        }

        RateLimiter::hit($key, $decaySeconds);

        sleep(random_int(1, 4));
    }
}
