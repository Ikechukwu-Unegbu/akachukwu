<?php

namespace App\Actions\Throttle;

use Illuminate\Support\Facades\Cache;

class ThrottleAction
{
    /**
     * Apply throttling logic for a specific action and user.
     *
     * @param string $actionName The unique name of the action (e.g., 'buyAirtime').
     * @param int $userId The ID of the user performing the action.
     * @param int $ttl Time-to-live in seconds for throttling (default: 60 seconds).
     * @throws \Exception
     */
    public function execute(string $actionName, int $userId, int $ttl = 60)
    {
        $key = $actionName . '_' . $userId;

        if (Cache::has($key)) {
            return response()->json([
                'message' => 'You are performing this action too frequently. Please wait a minute.',
            ], 429);
        }

        Cache::put($key, true, $ttl);

        sleep(random_int(1, 4));
    }
}
