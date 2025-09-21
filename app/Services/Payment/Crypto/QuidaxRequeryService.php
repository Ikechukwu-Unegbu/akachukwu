<?php 
namespace App\Services\Payment\Crypto;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiHelper;
use App\Services\Payment\Crypto\QuidaxxService;


class QuidaxRequeryService{

    protected $walletService;
    protected $quidaxService;

    public function __construct()
    {
        // $this->walletService = new WalletService();
        $this->quidaxService = new QuidaxxService();
    }


    public function reQueryDeposit($id)
    {
      
         $user = auth()->user();
    
        $result =  $this->quidaxService->makeRequest('get', "/users/{$user->quidax_id}/deposits/{$id}");
      
        return response()->json($result);
    }
}