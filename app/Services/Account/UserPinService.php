<?php

namespace App\Services\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserPinService
{
    public static function createPin(string $newPin, string $newPinConfirmation): bool
    {
        $user = Auth::user();

        if ($newPin !== $newPinConfirmation) {
            throw ValidationException::withMessages([
                'pin' => __('The new PIN confirmation does not match.'),
            ]);
        }

        if (!empty($user->pin) || !is_null($user->pin)) {
            throw ValidationException::withMessages([
                'pin' => __('PIN already created. Proceed to update PIN.'),
            ]);
        }

        self::updatePin($user, $newPin);
        return true;
    }

    public static function updatePinWithCurrent(string $currentPin, string $newPin, string $newPinConfirmation): bool
    {
        $user = Auth::user();

        if (!self::validatePin($user, $currentPin)) {
            throw ValidationException::withMessages([
                'current_pin' => __('The current PIN is incorrect.'),
            ]);
        }

        if ($newPin !== $newPinConfirmation) {
            throw ValidationException::withMessages([
                'pin' => __('The new PIN confirmation does not match.'),
            ]);
        }

        self::updatePin($user, $newPin);
        return true;
    }

    public static function validatePin($user, string $pin): bool
    {
        if (empty($user->pin) || is_null($user->pin)) {
            return true;
        }

        if (!Hash::check($pin, $user->pin)) {
            return false;
        }

        return true;
    }

    private static function updatePin($user, string $pin): void
    {
        $user->pin = Hash::make($pin);
        $user->save();
    }

    public static function resetPin($data)
    {
        $user = Auth::user();

        $currentPassword = $data['current_password'];
        $pin = $data['pin'];

        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('Your password does not match.'),
            ]);
        }

        self::updatePin($user, $pin);

        return true;
    }
}
