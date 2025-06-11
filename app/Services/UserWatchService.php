<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Services\Admin\Activity\ActivityLogService;

class UserWatchService
{

    protected const MAX_BALANCE = 10000;

    /**
     * Run full user watch process: match name, flag user, handle wallet control
     */
    public static function processKycValidation(User $user, array $kycResponse)
    {
        try {
            if (isset($kycResponse['firstName']) && isset($kycResponse['lastName'])) {
                $firstName = strtolower(trim($kycResponse['firstName']));
                $lastName = strtolower(trim($kycResponse['lastName']));
                $kycFullName1 = "{$firstName} {$lastName}";
                $kycFullName2 = "{$lastName} {$firstName}";
                $user->kyc_name = "{$kycResponse['firstName']} {$kycResponse['lastName']}";

            } elseif (isset($kycResponse['accountName'])) {

                $accountName = strtolower(trim($kycResponse['accountName']));
                $nameParts = explode(' ', $accountName);

                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[count($nameParts) - 1] ?? '';
                $kycFullName1 = "{$firstName} {$lastName}";
                $kycFullName2 = "{$lastName} {$firstName}";
                $user->kyc_name = ucwords($accountName);
                
            } else {
                throw new \Exception('KYC response does not contain recognizable name fields.');
            }

            $userName = strtolower(trim($user->name));
            $user->save();

            if ($userName !== $kycFullName1 && $userName !== $kycFullName2) {
                $user->is_flagged = true;
                $user->save();

                ActivityLogService::log([
                    'actor_id' => $user->id,
                    'activity' => 'flag',
                    'description' => "KYC name mismatch. KYC: '{$user->kyc_name}', User: '{$user->name}'",
                    'type' => 'KYC',
                    'raw_response' => json_encode($kycResponse),
                ]);
            }

        } catch (\Throwable $th) {
            Log::error("KYC validation error: " . $th->getMessage());
        }
    }



    /**
     * Watch all flagged users with balance > 10k and apply "post no debit"
     */
    public static function enforcePostNoDebit(User $user): void
    {
        if (
            $user->is_flagged &&
            $user->account_balance > self::MAX_BALANCE &&
            !$user->post_no_debit
        ) {
            $user->post_no_debit = true;
            $user->save();

            ActivityLogService::log([
                'actor_id' => $user->id,
                'activity' => 'post no debit',
                'description' => "Post No Debit applied to User {$user->id} due to flagged balance > 10k",
                'type' => 'PostNoDebit',
            ]);
        }
    }

    /**
     * Clear post no debit if support validates user identity manually
     */
    public static function clearPostNoDebit(User $user): void
    {
        $user->is_flagged = false;
        $user->post_no_debit = false;
        $user->save();

        ActivityLogService::log([
            'actor_id' => $user->id,
            'activity' => 'flag',
            'description' => "Post No Debit applied to User {$user->id} by support verification.",
            'type' => 'PostNoDebit',
        ]);
    }

    /**
     * Mark user as refunded and optionally blacklist
     */
    public static function refundToVictim(User $user): void
    {
        $user->is_blacklisted = true;
        $user->save();

        ActivityLogService::log([
            'actor_id' => $user->id,
            'activity' => 'flag',
            'description' => "User {$user->id} blacklisted and marked as refunded.",
            'type' => 'RefundToVictim',
        ]);
    }

    public static function blockIfPostNoDebit(User $user): bool
    {
        if ($user->post_no_debit) {
            return true;
        }

        return false;
    }
}
