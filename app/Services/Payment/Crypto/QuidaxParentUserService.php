<?php 
namespace App\Services\Payment\Crypto;
use App\Services\Payment\Crypto\QuidaxxService;

class QuidaxParentUserService{

    public $quidaxxService;
    public function __construct(QuidaxxService $quidaxxService)
    {
        $this->quidaxxService = $quidaxxService;
    }


    public function fetParentUser()
    {
        return $this->quidaxxService->makeRequest("get", "/users/me/");
    }

    public function fetchGivenWalletOfParentUser()
    {
        $this->quidaxxService->makeRequest('get', '');
    }

    public function createAllParentWallets()
    {
        $ngnResponse  = $this->quidaxxService->makeRequest('get', "/users/me/wallets/ngn/addresses?");
        $usdtResponse = $this->quidaxxService->makeRequest('get', "/users/me/wallets/udt/addresses?");
        $xrpResponse  = $this->quidaxxService->makeRequest('get', "/users/me/wallets/xrp/addresses?");
        $btcResponse  = $this->quidaxxService->makeRequest('get', "/users/me/wallets/btc/addresses?");
        $trxResponse  = $this->quidaxxService->makeRequest('get', "/users/me/wallets/trx/addresses?");
        $ethResponse  = $this->quidaxxService->makeRequest('get', "/users/me/wallets/eth/addresses?");
        $bnbResponse  = $this->quidaxxService->makeRequest('get', "/users/me/wallets/bnb/addresses?");
        $solResponse  = $this->quidaxxService->makeRequest('get', "/users/me/wallets/sol/addresses?");

        return [
            'ngn'  => $ngnResponse,
            'usdt' => $usdtResponse,
            'xrp'  => $xrpResponse,
            'btc'  => $btcResponse,
            'trx'  => $trxResponse,
            'eth'  => $ethResponse,
            'bnb'  => $bnbResponse,
            'sol'  => $solResponse,
        ];
    }


    public function getAllParentUserWalles()
    {
        $response = $this->quidaxxService->makeRequest('get', '/users/me/wallets');
        return $response;
    }

    public function fetchGivenCurrencyPaymentAddress($currency)
    {
        $response = $this->quidaxxService->makeRequest('get', "/users/{user_id}/wallets/{$currency}/address");
        return $response;
    }

    
}