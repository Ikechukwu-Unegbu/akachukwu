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

    public function withdraw(Request $request)
    {
        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . config('services.quidax.api_key', env('QUIDAX_API_KEY')),
        //     'accept'        => 'application/json',
        //     'content-type'  => 'application/json',
        // ])->post("https://www.quidax.com/api/v1/users/f723ef71-d748-4a54-bcf5-b1a6f0de2453/withdraws", [
        //     "currency"         => "usdt",
        //     "amount"           => "1",
        //     "transaction_note" => "Stay safe",
        //     "narration"        => "We love you.",
        //     "fund_uid"         => "0x6e897cD79921702b529F9f07A6F8DFb6ee9dECEF", // replace with wallet/subaccount
        //     "reference"        => uniqid("quidax_", true), // generate unique reference
        // ]);

        $response = Http::withHeaders([
    'Authorization' => 'Bearer ' . config('services.quidax.api_key'),
     'Accept' => 'application/json',
            'Content-Type' => 'application/json',
])->get("https://www.quidax.com/api/v1/users/me");


// dd($response->status(), $response->json());


        // return $response;
         return response()->json($response->json(), $response->status());
        // return response()->json($response->getBody());
    }

    public function transfer(Request $request)
    {
        // dd($request->all());
        $response = $this->transferService->transferFunds(
            1, "usdt", "Test transaction note",
            "Test narration",
            "0x6e897cD79921702b529F9f07A6F8DFb6ee9dECEF",
            "f723ef71-d748-4a54-bcf5-b1a6f0de2453",
            "bep20"
        );

        return $response;
    }


}
