<?php 
namespace App\Services\Payment\Crypto;

use App\Services\Payment\Crypto\QuidaxService;

class QuidaxSwapService{


    protected $quidaxService; 

    public function __construct(QuidaxService $quidaxService)
    {
        $this->quidaxService = $quidaxService;
    }

    /**
     * This method when called generates the rate for swapping the $fromCurrency to $toCurrency
     * **/ 
    public function generateSwapQuotation($userQuidaxId, $fromCurrency, $froAmount, $toCurrency, $toAmount=null)
    {
        $response = $this->quidaxService->makeRequest('get', "/users/{$userQuidaxId}/swap_quotation", [
            'from_currency'=>$fromCurrency,
            'from_amount'=>$froAmount,
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

}