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
        // dd($request->all());
        $result = $this->transferService->transferFunds(
            0.49, "usdt", "Test transaction note",
            "Test narration",
            "f723ef71-d748-4a54-bcf5-b1a6f0de2453",//reciever
            "0b256bf0-90f0-4682-9c87-58f228852b2f",//sender
            "bep20"
        );

         if($result->status == true && $result->response->status =='success'){
            return $result;
        }

        // dd($result->response)
       
    }


}
