<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Payment\Crypto\QuidaxParentUserService;
use App\Services\Payment\Crypto\QuidaxxService;
// use App\Services\Payment\Crypto\QuidaxxService;
use App\Services\Payment\Crypto\WalletService;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\Crypto\QuidaxTransferService;

class QuidaxParentUserController extends Controller
{

    public $parentService; 
    public $quidaxService;

    public function __construct(QuidaxParentUserService $parentService, QuidaxxService $quidaxService, QuidaxTransferService $transferService)
    {
        $this->parentService = $parentService;
        $this->quidaxService = $quidaxService;
        $this->transferService = $transferService;
    }


    public function getAllParentWallets()
    {
        return $this->parentService->getAllParentUserWalles();
    }

    public function createAllParentWallets()
    {
        return $this->parentService->createAllParentWallets();

    }

    public function fetchGivenCurrencyPaymentAddress($currency)
    {
        return $this->parentService->fetchGivenCurrencyPaymentAddress($currency);
    }


    public function generateSwapQuotation(Request $request)
    {
        // return response()->json($request->all());
    
        $request->validate([
            'from_currency' => 'required|string',
            'from_amount'   => 'required|numeric|min:0.00000001',
            'to_currency'   => 'required|string',
        ]);


       
        $result = $this->quidaxService->makeRequest('post', "/users/me/swap_quotation", [
            'from_currency'=>$request->from_currency,
            'from_amount'=>$request->from_amount,
            'to_currency'=>$request->to_currency
        ]);
        // return $result;
       Log::warning('Quidax swap gen: ',  (array)$result->response);
       
        if($result->response->status == true || $result->response->status == 'success'){
            $swaid = $result->response->data->id;
            $userQuidaxId = $result->response->data->user->id;
            $confirm = $this->quidaxService->makeRequest('POST', "/users/me/swap_quotation/{$swaid}/confirm");
       

            return response()->json([
                'status'=>'success',
                'message'=>'Swap successful',
                'data'=> $confirm       ]);
        }
        
        // return response
    }


    public function transfer(Request $request)
    {
     
        $result = $this->transferService->transferFunds(
            $request->amount, $request->currency, "Test transaction note",
            "Test narration",
            $request->receiver,//reciever
            "me",//config('services.quidax.master_account_id', env('QUIDAX_MASTER_ACCOUNT_ID')),//"f723ef71-d748-4a54-bcf5-b1a6f0de2453",//sender
            // "f723ef71-d748-4a54-bcf5-b1a6f0de2453",//sender
        );

        
        return $result;
        
       
    }
}
