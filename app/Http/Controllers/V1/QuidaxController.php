<?php

namespace App\Http\Controllers\V1;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Payment\Crypto\WalletService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuidaxController extends Controller
{
    protected $walletService;

    public function __construct()
    {
        $this->walletService = new WalletService();
    }

    public function createUser(Request $request)
    {
        $user = Auth::user();
        $name = explode(' ', $user->name);

        $data = [
            "email" => $user->email,
            "first_name" => $name[0],
            "last_name" => $name[1] ,
            "phone_number" => $user->phone
        ];

        $result=  $this->quidaxService->createUser($data, $user);
      
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
