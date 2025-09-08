<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\Payment\Crypto\WalletService;
use App\Services\Payment\Crypto\QuidaxxService;
use App\Services\Payment\Crypto\QuidaxTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Get user account information
     */
    public function getAccountInfo()
    {
        $result = $this->walletService->getAccountInfo();
        return response()->json($result);
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
     * Get user specific currency wallet
     */
    public function getUserWalletsCurrencyAddress($currency)
    {
        $result = $this->quidaxService->getUserWalletsCurrencyAddress($currency);
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
        $result =  $this->quidaxService->makeRequest('get', "/users/{$user->quidax_id}/{$currency}/btc/addresses");
      
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
     * Ensure the authenticated user has a Quidax user profile; create if missing
     */
    public function ensureUser()
    {
        $user = auth()->user();
        if (!empty($user->quidax_id)) {
            return response()->json([ 'status' => true, 'message' => 'Quidax user already exists', 'response' => [ 'quidax_id' => $user->quidax_id ] ]);
        }

        $result = $this->walletService->createUser();
        return response()->json($result);
    }

    /**
     * Ensure a wallet address exists for the specified currency; creates Quidax user first if needed
     */
    public function ensureWalletAddress($currency)
    {
        $user = auth()->user();
        if (empty($user->quidax_id)) {
            $this->walletService->createUser();
            $user->refresh();
        }

        $result = $this->quidaxService->getUserWalletsAddress($currency);
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

    /**
     * Initiate a deposit for a given crypto by ensuring user and returning the deposit address
     * Body: { currency: string }
     */
    public function initiateDeposit(Request $request)
    {
        $request->validate([
            'currency' => 'required|string',
        ]);

        $user = auth()->user();
        $currency = strtoupper($request->input('currency'));

        // Ensure Quidax user exists
        if (empty($user->quidax_id)) {
            $this->walletService->createUser();
            $user->refresh();
        }

        // Always hit the explicit address endpoint to generate/return address if missing
        $result = $this->quidaxService->makeRequest('get', "/users/{$user->quidax_id}/wallets/{$currency}");
        return response()->json($result);
    }

    /**
     * Transfer NGN from the user's Quidax NGN wallet to the platform NGN account.
     * Body: { amount: number|string, transaction_note?: string, narration?: string, fund_uid?: string }
     * If fund_uid is not provided, we fetch platform account via /users/me and try to use its identifier.
     */
    public function transferToPlatformNgn(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'transaction_note' => 'nullable|string',
            'narration' => 'nullable|string',
            'fund_uid' => 'nullable|string',
        ]);

        $user = auth()->user();
        if (empty($user->quidax_id)) {
            // Ensure Quidax user exists
            (new WalletService())->createUser();
            $user->refresh();
        }

        $amount = (string) $request->input('amount');
        $transactionNote = $request->input('transaction_note', 'NGN settlement');
        $narration = $request->input('narration', 'Settlement to platform NGN');

        $quidax = new QuidaxxService();
        $fundUid = $request->input('fund_uid');
        if (!$fundUid) {
            // Fetch platform account (/users/me) and attempt to derive a transferable identifier
            $me = $quidax->makeRequest('get', '/users/me');
            // Attempt common identifiers (depends on Quidax payload shape)
            $fundUid = $me->response->data->sn ?? ($me->response->data->uid ?? null);
        }

        $transferService = new QuidaxTransferService(new QuidaxxService());
        $result = $transferService->transferFunds(
            $amount,
            'NGN',
            $transactionNote,
            $narration,
            $fundUid,
            $user->quidax_id
        );

        return response()->json($result);
    }
}
