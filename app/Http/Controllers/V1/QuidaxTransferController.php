<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\Crypto\QuidaxTransferService;
use Illuminate\Support\Facades\Http;

class QuidaxTransferController extends Controller
{

    public $transferService;

    public function __construct(QuidaxTransferService $transferService)
    {
        $this->transferService = $transferService;
    }

  

    public function transfer(Request $request)
    {
     
        $result = $this->transferService->transferFunds(
            1, "usdt", "Test transaction note",
            "Test narration",
            "f723ef71-d748-4a54-bcf5-b1a6f0de2453",//reciever
            "me",//sender
        );

        
        return $result;
        
       
    }


}
