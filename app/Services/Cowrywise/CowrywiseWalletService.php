<?php

namespace App\Services\Cowrywise;

use App\Helpers\ApiHelper;
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


    public static function createWallet($accountId)
    {
        return static::cowryWiseApiCall(
            [
                    'account_id' => $accountId,
                    'currency_code' => static::CURRENCY
                ],
            "wallets",
            "Wallet Created Successfully",
            "create wallet"
        );
    }
}