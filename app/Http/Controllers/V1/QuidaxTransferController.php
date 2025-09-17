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
            7000, "ngn", "Test transaction note",
            "Test narration",
            "f7739a42-91ff-4239-9295-17f0f69ae5e3",//reciever
            "0b256bf0-90f0-4682-9c87-58f228852b2f",//sender
            "bep20"
        );

         if($result->status == true && $result->response->status =='success'){
            return $result;
        }

        // dd($result->response)
       
    }


}
