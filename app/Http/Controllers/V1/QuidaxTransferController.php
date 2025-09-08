<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\Crypto\QuidaxTransferService;

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
        $response = $this->transferService->transferFunds(
            '1', 'usdt', 'Test transaction note',
            'Test narration', '0x6e897cD79921702b529F9f07A6F8DFb6ee9dECEF',//parent user id
            'f723ef71-d748-4a54-bcf5-b1a6f0de2453',//Unegbu sub user
        );
        return $response;
    }
}
