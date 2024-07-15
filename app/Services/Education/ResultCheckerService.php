<?php

namespace App\Services\Education;

use App\Models\Vendor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Education\ResultChecker;
use App\Services\Account\AccountBalanceService;
use App\Models\Education\ResultCheckerTransaction;
use Illuminate\Support\Facades\Log;

class ResultCheckerService
{
    protected static $userWallet;
    protected static $vendor;

    protected CONST LIVE = "https://api-service.vtpass.com/api/pay";
    protected CONST TEST = "https://sandbox.vtpass.com/api/pay";
    
    public static function headers()
    {
        return [
            "Accept"      =>    "application/json",
            "api-key"     =>    self::$vendor->token,
            "public-key"  =>    self::$vendor->public_key,
            "secret-key"  =>    self::$vendor->secret_key
        ];
    }

    public static function initialize()
    {
        self::$vendor = Vendor::where('name', 'VTPASS')->first();
        self::$userWallet = new AccountBalanceService(Auth::user());
        return;
    }

    public static function url($data = [])
    {
        $url = self::getUrl();

        $response = Http::withHeaders(self::headers())->post($url, $data);

        return $response->object();
    }

    public static function create($exam, $quantity)
    {
        try {
            if ( (int) $quantity > 5 ) {
                return response()->json([
                    'status'    => false,
                    'error'     => [],
                    'message'   => "No. of pins out of range allowed. Kindly note that valid values are 1,2,3,4,5"
                ], 401)->getData();
            }
    
            self::initialize();
    
            $resultCheckerModel = ResultChecker::where('vendor_id', self::$vendor->id)->where('name', $exam)->first();
            $amount = ($quantity * $resultCheckerModel->amount);
    
            if (! self::$userWallet->verifyAccountBalance($amount)) 
                return response()->json([
                    'status'  => false,
                    'error' => 'Insufficient Account Balance.',
                    'message' => "You need at least ₦{$amount} to purchase this plan. Please fund your wallet to continue."
                ], 401)->getData();
    
            $resultCheckerModel = ResultChecker::where('vendor_id', self::$vendor->id)->where('name', $exam)->first();
    
            $transaction = ResultCheckerTransaction::create([
                'vendor_id'         =>  self::$vendor->id,
                'result_checker_id' =>  $resultCheckerModel->id,
                'exam_name'         =>  $resultCheckerModel->name,
                'quantity'          =>  $quantity,
                'amount'            =>  $amount,
                'balance_before'    =>  Auth::user()->account_balance,
                'balance_after'     =>  Auth::user()->account_balance
            ]);
            
            $data = [
                "request_id"     =>  $transaction->reference_id,
                "serviceID"      =>  Str::lower($resultCheckerModel->name),
                "variation_code" =>  "waecdirect",
                "quantity"       =>  $quantity,
                "phone"          =>  "08020536913"
            ];

            $response = self::url($data);
            
            if (isset($response->content->transactions) && $response->content->transactions->status === "delivered") {
                foreach ($response->cards as $card) {
                    $transaction->result_checker_pins()->create([
                        'serial' => $card->Serial,
                        'pin' => $card->Pin
                    ]);
                }
                self::$userWallet->transaction($amount);
                $transaction->update([
                    'balance_after'     =>    self::$userWallet->getAccountBalance(),
                    'api_data_id'       =>    $response->content->transactions->transactionId,
                    'api_response'      =>    $response->purchased_code,
                    'status'            =>    true,
                ]);

                return response()->json([
                    'status'   =>    true,
                    'error'    =>    NULL,
                    'message'  =>    "Result Checker PIN purchase successful: {$transaction->exam_name} ($transaction->quantity QTY) ₦{$amount}.",
                    'response' =>    $transaction
                ], 200)->getData();
            }
            return response()->json([
                'status'    => false,
                'error'     => 'Server Error',
                'message'   => "Opps! Unable to Perform result checker PIN. Please try again later."
            ], 401)->getData();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'status'    => false,
                'error'     => $th->getMessage(),
                'message'   => "Opps! Unable to perform result checker PIN payment. Please check your network connection."
            ])->getData();
        }
    }

    public static function getUrl()
    {
        if (app()->environment() == 'production') {
            return static::$vendor->api;
        }

        return static::TEST;
    }


}