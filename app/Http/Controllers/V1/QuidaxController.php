<?php

namespace App\Http\Controllers\V1;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Payment\Crypto\WalletService;

class QuidaxController extends Controller
{
    protected $walletService;

    public function __construct()
    {
        $this->walletService = new WalletService();
    }

    public function createUser(Request $request)
    {
        $result = $this->walletService->createUser();
        return response()->json($result);
    }

    public function getUsers(Request $request)
    {
        $result = $this->walletService->fetchUsers();
        return response()->json($result);
    }

    /**
     * Get user account information
     */
    public function getAccountInfo()
    {
        $user = auth()->user();
        if ($user->quidax_id) {
            $result = $this->walletService->getAccountInfo($user->quidax_id);
            return response()->json($result);
        }
        return ApiHelper::sendError(['message' => 'User not found'], 'User not found');
    }

    /**
     * Get user wallets
     */
    public function getUserWallets()
    {
        $result = $this->walletService->getUserWallets();
        return response()->json($result);
    }

    /**
     * Get specific wallet balance
     */
    public function getWalletBalance(Request $request, $currency)
    {
        $result = $this->walletService->getWalletBalance($currency);
        return response()->json($result);
    }

    /**
     * Get account balance summary
     */
    public function getAccountBalanceSummary()
    {
        $result = $this->walletService->getAccountBalanceSummary();
        return response()->json($result);
    }

      /**
     * Get supported currencies
     */
    public function getSupportedCurrencies()
    {
        $result = $this->walletService->getSupportedCurrencies();
        return response()->json($result);
    }

    /**
     * Get currency info
     */
    public function getCurrencyInfo(Request $request, $currency)
    {
        $result = $this->walletService->getCurrencyInfo($currency);
        return response()->json($result);
    }

     /**
     * Get wallet statistics
     */
    public function getWalletStats()
    {
        $result = $this->walletService->getWalletStats();
        return response()->json($result);
    }
}
