<?php

namespace App\Services\Cowrywise;

use App\Helpers\ApiHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CowrywiseWalletService extends CowrywiseBaseService
{
    public static function fetchSingleWallet($walletId)
    {
        return static::cowryWiseGeApiCall(
            "api/v1/wallets/{$walletId}",
            'Wallet retrieved successfully'
        );
    }

    public static function fetchAlleWallet()
    {
        return static::cowryWiseGeApiCall(
            "api/v1/wallets",
            'Wallets retrieved successfully'
        );
    }


    public static function createWallet(User $user)
    {
        $response = static::cowryWiseApiCall(
            [
                    'account_id' => $user->cowryWiseAccount->account_id,
                    'currency_code' => static::CURRENCY
                ],
            "wallets",
            "Wallet Created Successfully",
            "create wallet"
        );

        if (isset($response['status']) && $response['status']) {
            self::cowryWiseWallet($user, $response['response']['data']);
            return ApiHelper::sendResponse($response['response']['data'], "Wallet added successfully");
        }

        return ApiHelper::sendError(["Failed to create Wallet!"], [
            'error' => "Failed to create Wallet",
            'details' => $response,
        ], 401);
    }
}
