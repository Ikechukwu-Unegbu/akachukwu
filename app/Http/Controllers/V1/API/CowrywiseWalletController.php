<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use App\Services\Cowrywise\CowrywiseWalletService;
use Illuminate\Http\Request;

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

    public function create($accountId)
    {
        return $this->cowrywiseWalletService::createWallet($accountId);
    }
}
