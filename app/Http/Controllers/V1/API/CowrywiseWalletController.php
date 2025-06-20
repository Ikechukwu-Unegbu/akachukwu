<?php

namespace App\Http\Controllers\V1\API;

use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Cowrywise\CowrywiseWalletService;

class CowrywiseWalletController extends Controller
{
    protected ?CowrywiseWalletService $cowrywiseWalletService;

    public function __construct(CowrywiseWalletService $cowrywiseWalletService)
    {
        $this->cowrywiseWalletService  = $cowrywiseWalletService;
    }

    public function fetchAllWallet()
    {
        return $this->cowrywiseWalletService::fetchAlleWallet();
    }

    public function fetchWallet($walletId)
    {
        return $this->cowrywiseWalletService::fetchSingleWallet($walletId);
    }

    public function create()
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        return $this->cowrywiseWalletService::createWallet($user);
    }
}
