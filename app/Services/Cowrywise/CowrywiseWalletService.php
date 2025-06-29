<?php

namespace App\Services\Cowrywise;

use App\Helpers\ApiHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CowrywiseWalletService extends CowrywiseBaseService
{
    public static function fetchSingleWallet($user)
    {
        return ApiHelper::sendResponse($user->cowryWiseAccount->wallets, 'Wallet retrieved successfully');
        // return static::cowryWiseGeApiCall(
        //     "api/v1/wallets/{$walletId}",
        //     'Wallet retrieved successfully'
        // );
    }

    public static function fetchAllWallet()
    {
        $page = request()->page ?? 1;
        return static::cowryWiseGeApiCall(
            "api/v1/wallets",
            'Wallets retrieved successfully',
            [],
            ['page' => $page]
        );
    }


    public static function createWallet(User $user)
    {
        $data = [
            'account_id' => $user->cowryWiseAccount->account_id,
            'currency_code' => static::CURRENCY
        ];

        // $response = static::cowryWiseApiCall(
        //     $data,
        //     "wallets",
        //     "Wallet Created Successfully",
        //     "create wallet"
        // );

        // if (isset($response['status']) && $response['status']) {
        //     self::cowryWiseWallet($user, $response['response']['data']);
        //     return ApiHelper::sendResponse($response['response']['data'], "Wallet added successfully");
        // }

        return ApiHelper::sendError(["Failed to create Wallet!"], [
            'error' => "Failed to create Wallet",
            // 'details' => $response,
        ], 401);
    }
}
