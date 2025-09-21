<?php

namespace App\Http\Controllers\V1;


use App\Services\Payment\Crypto\QuidaxxService;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Payment\Crypto\WalletService;

class QuidaxController extends Controller
{
    protected $walletService;
    protected $quidaxService;

    public function __construct()
    {
        $this->walletService = new WalletService();
        $this->quidaxService = new QuidaxxService();
    }

    public function createUser(Request $request)
    {
        // dd(config('services.quidax.api_key', env('QUIDAX_SECRET_KEY')));
        $result = $this->walletService->createUser();
        if($result->status==false){
            return response()->json($result);
        }
        
       
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
        $result = $this->quidaxService->getUserWallets();
        return response()->json($result);
    }

    /**
     * Get specific wallet balance
     */
    public function getWalletBalance(Request $request, $currency)
    {
        $result = $this->quidaxService->getWalletBalance($currency);
        return response()->json($result);
    }


    /**
     * 
     * **/ 
    public function generateWalletAddress($currency)
    {
        // dd('hello');
         $result = $this->quidaxService->getUserWalletsAddress($currency);
        return response()->json($result);
    }

    public function generateWalletAddressess($currency)
    {
        // dd('hello');
         $user = auth()->user();
        // dd($user->quidax_id);
        $result =  $this->quidaxService->makeRequest('get', "/users/{$user->quidax_id}/{$currency}/addresses");
      
        return response()->json($result);
    }

    public function reQueryDeposit($id)
    {
        // dd('hello');
         $user = auth()->user();
        // dd($user->quidax_id);
        $result =  $this->quidaxService->makeRequest('get', "/users/{$user->quidax_id}/deposits/{$id}");
        dd($result->response->status, $result->response->data);
        //  dd();
        return response()->json($result);
    }

    /**
     * Get account balance summary
     */
    public function getAccountBalanceSummary()
    {
        $result = $this->quidaxService->getAccountBalanceSummary();
        return response()->json($result);
    }

      /**
     * Get supported currencies
     */
    public function getSupportedCurrencies()
    {
        $result = $this->quidaxService->getSupportedCurrencies();
        return response()->json($result);
    }

    /**
     * Get currency info
     */
    public function getCurrencyInfo(Request $request, $currency)
    {
        $result = $this->quidaxService->getCurrencyInfo($currency);
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
