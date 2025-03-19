<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UserDeviceRepository
{
    public function updateOrCreate($user, $input)
    {
        try {
            if ( ! $user->os_player_id) {
                $user->update([
                    'os_player_id' => $input['os_player_id'],
                    'device_type'  => $input['device_type'] ?? NULL,
                ]);

                return $user;
            }

            if ($user->os_player_id && $user->os_player_id !== $input['os_player_id']) {
                return UserDevice::updateOrCreate([
                    'os_player_id'  => $input['os_player_id'],
                ], [
                    'user_id'       =>  $user->id,
                    'os_player_id'  =>  $input['os_player_id'],
                    'device_type'   =>  $input['device_type']  ?? NULL
                ]);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateStatus($playerId) : bool
    {
        $userDevice = UserDevice::whereOsPlayerId($playerId)->first();
        $userDevice->update(['is_active' => !$userDevice->is_active]);

        return true;
    }
}