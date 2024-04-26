<?php

namespace App\Services\Electricity;

use Illuminate\Support\Str;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Utility\ElectricityTransaction;
use App\Services\Account\AccountBalanceService;

class ElectricityService 
{
    private static $account;

    public static function create($vendorId, $discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress)
    {
        try {

            if ($amount < 500) {
                return response()->json([
                    'status'    => false,
                    'error'     => 'Minimum account error',
                    'message'   => "The minimum amount is ₦500"
                ], 401)->getData();
            }

            self::$account = new AccountBalanceService(Auth::user());

            if (! self::$account->verifyAccountBalance($amount)) {
                return response()->json([
                    'status'    =>   false,
                    'error'     =>  'Insufficient Account Balance.',
                    'message'   =>  "You need at least ₦{$amount} to purchase this plan. Please fund your wallet to continue.",
                ], 401)->getData();
            }
            
            $vendor = DataVendor::find($vendorId);
            $electricity = Electricity::whereVendorId($vendor->id)->whereDiscoId($discoId)->first();

            $transaction = ElectricityTransaction::create([
                'user_id'                   =>  Auth::id(),
                'vendor_id'                 =>  $vendor->id,
                'disco_id'                  =>  $electricity->disco_id,
                'disco_name'                =>  $electricity->disco_name,
                'meter_number'              =>  $meterNumber,
                'meter_type_id'             =>  $meterType,
                'meter_type_name'           =>  $meterType == 1 ? 'Prepaid' : 'Postpaid',
                'amount'                    =>  $amount,
                'customer_mobile_number'    =>  $customerMobile,
                'customer_name'             =>  $customerName,
                'customer_address'          =>  $customerAddress,
                'balance_before'            =>  Auth::user()->account_balance
            ]);

            $response = Http::withHeaders([
                'Authorization' => "Token " . $vendor->token,
                'Content-Type' => 'application/json',
            ])->post("{$vendor->api}/billpayment/", [
                'disco_name'        => $transaction->disco_id,
                'meter_number'      => $transaction->meter_number,
                'amount'            => $amount,
                'MeterType'         => $transaction->meter_type_name,
                'Customer_Phone'    => $transaction->customer_mobile_number,
                'customer_name'     => $transaction->customer_name,
                'customer_address'  => $transaction->customer_address
            ]);

            $response = $response->object();
            
            if (isset($response->error)) {
                // Insufficient API Wallet Balance Error
                return response()->json([
                    'status'  => false,
                    'error'   => 'Insufficient Balance From API.',
                    'message' => "An error occurred during bill payment request. Please try again later."
                ], 401)->getData();
            }
               
            if (isset($response->Status) && $response->Status == 'successful') {    
                self::$account->transaction($response->amount);    
                $transaction->update([
                    'balance_after'     =>    self::$account->getAccountBalance(),
                    'status'            =>    true,
                    'api_data_id'       =>    $response->ident ?? NULL,
                ]);

                return response()->json([
                    'status'  =>    true,
                    'error'   =>    NULL,
                    'message' =>    "Bill Payment was Successful. You purchased ₦{$transaction->amount} for {$transaction->meter_type_name} to ({$transaction->meter_number})."
                ], 200)->getData();
            }

            return response()->json([
                'status'    => false,
                'error'     => 'Server Error',
                'message'   => "Opps! Unable to Perform transaction. Please try again later."
            ], 401)->getData();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'status'    =>   false,
                'error'     =>  'network connection error',
                'message'   =>  'Opps! Unable to payment payment. Please check your network connection.',
            ])->getData();
        }        
    }

    public static function validateMeterNumber($vendorId, $meterNumber, $discoId, $meterType) 
    {
        try {

            $vendor = DataVendor::find($vendorId);
            $meterType =  $meterType == 1 ? 'Prepaid' : 'Postpaid';

            $disco = Electricity::whereVendorId($vendor->id)->whereDiscoId($discoId)->first()->disco_name;

            $response = Http::withHeaders([
                'Authorization' => "Token " . $vendor->token,
                'Content-Type' => 'application/json',
            ])->get("{$vendor->api}/validatemeter?meternumber={$meterNumber}&disconame={$disco}&mtype={$meterType}");
            
            $response = $response->object();

            if (!$response->invalid) {               
                return response()->json([
                    'status'    =>  true,
                    'error'     =>  null,
                    'message'   =>  'Meter Number validated. Proceed to make payment.',
                    'data'      =>  $response
                ])->getData();
            }

            return response()->json([
                'status'    =>   false,
                'error'     =>   'Meter number error',
                'message'   =>   'Invalid meter number. Provide a valid meter number.',
            ], 401)->getData();

        }  catch (\Throwable $th) {

            Log::error($th->getMessage());

            return response()->json([
                'status'    =>   false,
                'error'     =>  'network connection error',
                'message'   =>  'Opps! Unable to validate number. Please check your network connection.',
            ])->getData();

        }
    }
}