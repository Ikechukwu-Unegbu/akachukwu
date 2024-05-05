<?php

namespace App\Services\Cable;

use App\Models\Utility\Cable;
use App\Models\Data\DataVendor;
use App\Models\Utility\CablePlan;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\ElectricityTransaction;
use App\Services\Account\AccountBalanceService;
use App\Services\Beneficiary\BeneficiaryService;

class CableService
{
    private static $account;

    public static function create($vendorId, $cableId, $cablePlan, $iucNumber, $customer)
    {
        try {

            $vendor = DataVendor::find($vendorId);
            $cable = Cable::whereVendorId($vendor->id)->whereCableId($cableId)->first();
            $cable_plan = CablePlan::whereVendorId($vendor->id)->whereCablePlanId($cablePlan)->first();

            self::$account = new AccountBalanceService(Auth::user());
            if (!self::$account->verifyAccountBalance($cable_plan->amount)) {
                return response()->json([
                    'status'    =>   false,
                    'error'     =>  'Insufficient Account Balance.',
                    'message'   =>  "You need at least ₦{$cable_plan->amount} to purchase this plan. Please fund your wallet to continue.",
                ], 401)->getData();
            }

            $transaction = CableTransaction::create([
                'user_id'             =>  Auth::id(),
                'vendor_id'           =>  $vendor->id,
                'cable_name'          =>  $cable->cable_name,
                'cable_id'            =>  $cable->cable_id,
                'cable_plan_name'     =>  $cable_plan->package,
                'cable_plan_id'       =>  $cable_plan->cable_plan_id,
                'smart_card_number'   =>  $iucNumber,
                'customer_name'       =>  $customer,
                'amount'              =>  $cable_plan->amount,
                'balance_before'      =>  Auth::user()->account_balance,
            ]);

            $response = Http::withHeaders([
                'Authorization' => "Token " . $vendor->token,
                'Content-Type' => 'application/json',
            ])->post("{$vendor->api}/cablesub/", [
                'cablename'         =>  $cable->cable_id,
                'cableplan'         =>  $cable_plan->cable_plan_id,
                'smart_card_number' =>  $iucNumber
            ]);

            $response = $response->object();

            if (isset($response->error)) {
                // Insufficient API Wallet Balance Error
                return response()->json([
                    'status'  => false,
                    'error'   => 'Insufficient Balance From API.',
                    'message' => "An error occurred during cable payment request. Please try again later."
                ], 401)->getData();
            }

            if (isset($response->Status) && $response->Status == 'successful') {

                self::$account->transaction($response->transaction->amount);

                $transaction->update([
                    'balance_after'     =>    self::$account->getAccountBalance(),
                    'status'            =>    true,
                    'api_data_id'       =>    $response->response->ident ?? NULL,
                ]);
                BeneficiaryService::create($transaction->smart_card_number, 'cable', $transaction);
                return response()->json([
                    'status'    =>    true,
                    'error'     =>    NULL,
                    'message'   =>    "Cable subscription successful: {$transaction->cable_plan_name} for ₦{$transaction->amount} on {$transaction->customer_name} ({$transaction->smart_card_number}).",
                    'response'  =>    $transaction
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
            ], 401)->getData();
        }
    }

    public static function validateIUCNumber($vendorId, $iucNumber, $cableName)
    {
        try {

            $vendor = DataVendor::find($vendorId);
            $cable = Cable::whereVendorId($vendor->id)->whereCableId($cableName)->first()?->cable_name;

            $response = Http::withHeaders([
                'Authorization' => "Token " . $vendor->token,
                'Content-Type' => 'application/json',
            ])->get("{$vendor->api}/validateiuc/?smart_card_number={$iucNumber}&cablename={$cable}");

            $response = $response->object();

            if (!$response->invalid) {
                return response()->json([
                    'status'    =>  true,
                    'error'     =>  null,
                    'message'   =>  'IUC validated. Proceed to make payment.',
                    'data'      =>  $response
                ])->getData();
            }

            return response()->json([
                'status'    =>   false,
                'error'     =>   'IUC/SMARTCARD error',
                'message'   =>   'Invalid IUC/SMARTCARD. Please provide a valid IUC/SMARTCARD.',
            ], 401)->getData();
        } catch (\Throwable $th) {

            Log::error($th->getMessage());

            return response()->json([
                'status'    =>   false,
                'error'     =>  'network connection error',
                'message'   =>  'Opps! Unable to validate IUC/SMARTCARD. Please check your network connection.',
            ])->getData();
        }
    }
}
