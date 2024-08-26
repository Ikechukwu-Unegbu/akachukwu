<?php 
namespace App\Helpers;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GeneralHelpers{
        /**
     * Generate a unique UUID for the given table.
     *
     * @param string $table
     * @return string
     */
    public static function generateUniqueUuid(string $table): string
    {
        $uuid = Str::uuid();

        while (DB::table($table)->where('uuid', $uuid)->exists()) {
            $uuid = Str::uuid();
        }

        return $uuid;
    }
    public static function generateUniqueRef(string $table): string
    {
        $uuid = Str::uuid();

        while (DB::table($table)->where('reference_id', $uuid)->exists()) {
            $uuid = Str::uuid();
        }

        return $uuid;
    }

     /**
     * Determine if the given string is an email address or a username.
     *
     * @param string $input
     * @return string
     */
    public  function identifyStringType(string $input): string
    {
        // Define a simple email validation rule
        $emailRule = 'email';

        // Validate if the input is an email
        $validator = Validator::make(['input' => $input], [
            'input' => $emailRule,
        ]);

        if ($validator->passes()) {
            return 'Email';
        }

        return 'Username';
    }

    // public static function checkReferrer($request, $user)
    // {
    //     if ($request->filled('referral_code')) {
    //         $referrer = User::where('username', $request->referral_code)->first();
    
    //         if ($referrer) {
    //             Referral::create([
    //                 'referrer_id' => $referrer->id,
    //                 'referred_user_id' => $user->id,
    //                 // 'referral_code' => $request->referral_code,
    //                 'referred_email' => $user->email,
    //                 'status' => 'completed',
    //             ]);
    //         }
    //     }
    // }

    public static function checkReferrer($request, $user)
    {
        $existingReferral = Referral::where('referred_email', $user->email)
                                    ->where('status', '!=', 'completed')
                                    ->first();

        if ($existingReferral) {
            $existingReferral->update([
                'referred_user_id' => $user->id,
                'status' => 'completed',
            ]);
        } else {
            if ($request->filled('referral_code')) {
                $referrer = User::where('username', $request->referral_code)->first();

                if ($referrer) {
                    Referral::create([
                        'referrer_id' => $referrer->id,
                        'referred_user_id' => $user->id,
                        'referred_email' => $user->email,
                        'status' => 'completed',
                    ]);
                }
            }
        }
    }


}