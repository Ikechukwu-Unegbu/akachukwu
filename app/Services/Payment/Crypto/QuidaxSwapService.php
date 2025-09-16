<?php 
namespace App\Services\Payment\Crypto;

use App\Services\Payment\Crypto\QuidaxxService;
use App\Models\User;

class QuidaxSwapService{


    protected $quidaxService; 

    public function __construct(QuidaxxService $quidaxService)
    {
        $this->quidaxService = $quidaxService;
    }

    /**
     * This method when called generates the rate for swapping the $fromCurrency to $toCurrency
     * **/ 
    public function generateSwapQuotation($userQuidaxId, $fromCurrency, $fromAmount, $toCurrency, $toAmount=null)
    {
        
        $response = $this->quidaxService->makeRequest('post', "/users/{$userQuidaxId}/swap_quotation", [
            'from_currency'=>$fromCurrency,
            'from_amount'=>$fromAmount,
            'to_currency'=>$toCurrency
        ]);
        return $response;
    }

    /**
     * Confirm swap
     * */ 
    public function confirmQuidaxSwap($quotationId, $userQuidaxId)
    {
       
        // auth()->user()->quidax_id
        $response = $this->quidaxService->makeRequest('POST', "/users/{$userQuidaxId}/swap_quotation/{$quotationId}/confirm");
        return $response;
    }

    public function transferFundsToParentAccount($fromAmount, $f)
    {
        
        $user = User::where('quidax_id', $userQuidaxId)->first();

        $service = new QuidaxSwapService(new QuidaxxService());
        $result = $service->generateSwapQuotation(
            $user->quidax_id,
            $request->input('from_currency'),
            (string) $request->input('from_amount'),
            $request->input('to_currency')
        );
        // dd($result->response->data->user->id);

        if($result->response->status == true || $result->response->status == 'success'){
            $swaid = $result->response->data->id;
            $userQuidaxId = $result->response->data->user->id;
            $confirm = $service->confirmQuidaxSwap($swaid, $userQuidaxId);
       


        }
    }

}