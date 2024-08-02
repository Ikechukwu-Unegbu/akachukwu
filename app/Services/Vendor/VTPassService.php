<?php

namespace App\Services\Vendor;

use App\Models\Vendor;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Utility\Cable;
use App\Models\Data\DataNetwork;
use App\Models\Utility\CablePlan;
use App\Models\Utility\Electricity;
use App\Services\CalculateDiscount;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Education\ResultChecker;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\ElectricityTransaction;
use App\Services\Account\AccountBalanceService;
use App\Services\Beneficiary\BeneficiaryService;
use App\Models\Education\ResultCheckerTransaction;

class VTPassService
{
    protected static $vendor;
    protected static $authUser;

    protected const TEST = "https://sandbox.vtpass.com/api/pay";
    protected const WALLET_BALANCE = "/balance";
    protected const VERIFY_NUMBER = "/merchant-verify";

    protected const MTN_CORPORATE = "mtn-data";
    protected const MTN_SME = "mtn-data";
    protected const AIRTEL_CORPORATE = "airtel-data";
    protected const AIRTEL_SME = "airtel-data";
    protected const GLO_CORPORATE = "glo-data";
    protected const GLO_SME = "glo-sme-data";
    protected const ETISALAT_CORPORATE = "etisalat-data";
    protected const ETISALAT_SME = "9mobile-sme-data";

    public function __construct(Vendor $vendor)
    {
        self::$vendor = $vendor;
        self::$authUser = new AccountBalanceService(Auth::user());
    }

    public static function headers()
    {
        return [
            "Accept"      =>    "application/json",
            "api-key"     =>    self::$vendor->token,
            "public-key"  =>    self::$vendor->public_key,
            "secret-key"  =>    self::$vendor->secret_key
        ];
    }

    public static function url($data = [])
    {
        $url = self::getUrl();

        $response = Http::withHeaders(self::headers())->post($url, $data);

        return $response->object();
    }

    public static function getWalletBalance()
    {
        $url = Str::replace('/pay', '', self::getUrl()) . self::WALLET_BALANCE;

        $response = Http::withHeaders(self::headers())->get($url);

        $response = $response->object();

        if (isset($response->code) && $response->code) {
            return ApiHelper::sendResponse(number_format($response->contents->balance, 2), 'Wallet Balance Fetched Successfully');
        }

        return ApiHelper::sendError('', '');
    }

    public static function airtime($networkId, $amount, $mobileNumber)
    {
        try {

            if ($amount < 50) {
                $errorResponse = [
                    'error' => 'Insufficient Account Balance.',
                    'message' => "The minimum airtime topup is ₦50"
                ];
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            }

            $verifyAccountBalance = self::verifyAccountBalance($amount);
            if (!$verifyAccountBalance->status) {
                return ApiHelper::sendError($verifyAccountBalance->error, $verifyAccountBalance->message);
            }

            $network = DataNetwork::whereVendorId(self::$vendor->id)->whereNetworkId($networkId)->first();
            // Initiate Airtime Transaction
            $transaction = AirtimeTransaction::create([
                'vendor_id'         =>  self::$vendor->id,
                'network_id'        =>  $network->network_id,
                'network_name'      =>  $network->name,
                'amount'            =>  $amount,
                'mobile_number'     =>  $mobileNumber,
                'balance_before'    =>  Auth::user()->account_balance,
                'balance_after'     =>  Auth::user()->account_balance
            ]);

            $data = [
                'request_id'   => $transaction->transaction_id,
                'serviceID'    => Str::lower($network->name),
                'amount'       => $amount,
                'phone'        => $mobileNumber,
            ];

            $response = static::url($data);

            self::storeApiResponse($transaction, $response);

            if (isset($response->code) && isset($response->content->transactions->status) && $response->content->transactions->status === "failed") {
                // Insufficient API Wallet Balance Error
                $errorResponse = [
                    'error'   => 'Insufficient Balance From API.',
                    'message' => "An error occurred during the Airtime request. Please try again later."
                ];
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            }

            if (isset($response->code) && isset($response->content->transactions->status) && $response->content->transactions->status === "delivered") {

                if (auth()->user()->isReseller()) {
                    $amount = CalculateDiscount::applyDiscount($amount, 'airtime');
                }

                self::$authUser->transaction($amount);

                $transaction->update([
                    'balance_after'     =>    self::$authUser->getAccountBalance(),
                    'status'            =>    true,
                    'api_data_id'       =>    $response->content->transactions->transactionId,
                    // 'api_response'      =>    $response->response_description ?? NULL
                ]);

                BeneficiaryService::create($transaction->mobile_number, 'airtime', $transaction);

                return ApiHelper::sendResponse($transaction, "Airtime purchase successful: ₦{$amount} {$network->name} airtime added to {$mobileNumber}.");
            }

            $errorResponse = [
                'error'     => 'Server Error',
                'message'   => "Opps! Unable to Perform transaction. Please try again later.",
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {

            Log::error($th->getMessage());

            $errorResponse = [
                'error'     => $th->getMessage(),
                'message'   => "Opps! Unable to perform airtime payment. Please check your network connection."
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function data($networkId, $typeId, $dataId, $mobileNumber)
    {
        try {
            $vendor = self::$vendor;
            $network = DataNetwork::whereVendorId($vendor->id)->whereNetworkId($networkId)->first();
            $plan = DataPlan::whereVendorId($vendor->id)->whereNetworkId($network->network_id)->whereDataId($dataId)->first();
            $type = DataType::whereVendorId($vendor->id)->whereNetworkId($network->network_id)->whereId($typeId)->first();

            $verifyAccountBalance = self::verifyAccountBalance($plan->amount);
            if (!$verifyAccountBalance->status) {
                return ApiHelper::sendError($verifyAccountBalance->error, $verifyAccountBalance->message);
            }

            $transaction = DataTransaction::create([
                'user_id'            =>  Auth::id(),
                'vendor_id'          =>  $vendor->id,
                'network_id'         =>  $network->network_id,
                'type_id'            =>  $type->id,
                'data_id'            =>  $plan->data_id,
                'amount'             =>  $plan->amount,
                'size'               =>  $plan->size,
                'validity'           =>  $plan->validity,
                'mobile_number'      =>  $mobileNumber,
                'balance_before'     =>  Auth::user()->account_balance,
                'balance_after'      =>  Auth::user()->account_balance,
                'plan_network'       =>  $network->name,
                'plan_name'          =>  $plan->size,
                'plan_amount'        =>  $plan->amount
            ]);

            $serviceId = "";

            if ($type->dataNetwork->name === "MTN" && $type->name == "CORPORATE" || $type->name == "SME") {
                $serviceId = self::MTN_CORPORATE;
            }

            $data = [
                'request_id'       =>  $transaction->transaction_id,
                'serviceID'        =>  $plan->service_id,
                'billersCode'      =>  $transaction->mobile_number,
                'variation_code'   =>  $transaction->data_id,
                'amount'           =>  $transaction->amount,
                'phone'            =>  $transaction->mobile_number
            ];

            $response = self::url($data);

            self::storeApiResponse($transaction, $response);

            if (isset($response->code) && isset($response->content->transactions->status) && $response->content->transactions->status === "failed") {
                // Insufficient API Wallet Balance Error
                $errorResponse = [
                    'error'   => 'Insufficient Balance From API.',
                    'message' => "An error occurred during Data request. Please try again later."
                ];
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            }

            if (
                isset($response->code) &&
                isset($response->content->transactions->status) &&
                $response->content->transactions->status === "delivered"
            ) {

                $amount = $plan->amount;

                if (auth()->user()->isReseller()) {
                    $amount = CalculateDiscount::applyDiscount($amount, 'data');
                }

                self::$authUser->transaction($amount);

                $transaction->update([
                    'balance_after'     =>    self::$authUser->getAccountBalance(),
                    'status'            =>    true,
                    'plan_amount'       =>    $response->amount,
                    'api_data_id'       =>    $response->content->transactions->transactionId,
                    // 'api_response'      =>    $response->response_description ?? NULL
                ]);

                BeneficiaryService::create($transaction->mobile_number, 'data', $transaction);

                return ApiHelper::sendResponse($transaction, "Data purchase successful: {$network->name} {$plan->size} for ₦{$plan->amount} on {$mobileNumber}.");
            }

            $errorResponse = [
                'error'     => 'Server Error',
                'message'   => "Opps! Unable to Perform transaction. Please try again later."
            ];

            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            Log::error($th->getMessage());
            $errorResponse = [
                'error'     =>  'network connection error',
                'message'   =>  'Opps! Unable to make payment. Please check your network connection.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function electricity($discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress)
    {
        try {

            if ($amount < 500) {
                $errorResponse = [
                    'error'     => 'Minimum account error',
                    'message'   => "The minimum amount is ₦500"
                ];
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            }
            
            $verifyAccountBalance = self::verifyAccountBalance($amount);
            if (!$verifyAccountBalance->status) {
                return ApiHelper::sendError($verifyAccountBalance->error, $verifyAccountBalance->message);
            }

            $vendor = self::$vendor;

            $electricity = Electricity::whereVendorId($vendor->id)->whereDiscoId($discoId)->first();

            $transaction = ElectricityTransaction::create([
                'user_id'                   =>  Auth::id(),
                'vendor_id'                 =>  $vendor->id,
                'disco_id'                  =>  $electricity->disco_id,
                'disco_name'                =>  $electricity->disco_name,
                'meter_number'              =>  $meterNumber,
                'meter_type_id'             =>  $meterType,
                'meter_type_name'           =>  $meterType == 1 ? 'prepaid' : 'postpaid',
                'amount'                    =>  $amount,
                'customer_mobile_number'    =>  $customerMobile,
                'customer_name'             =>  $customerName,
                'customer_address'          =>  $customerAddress,
                'balance_before'            =>  Auth::user()->account_balance,
                'balance_after'             =>  Auth::user()->account_balance
            ]);

            $data = [
                "request_id"    =>  $transaction->transaction_id,
                "serviceID"     =>  $transaction->disco_id,
                "billersCode"   =>  $transaction->meter_number,
                "variation_code" =>  $transaction->meter_type_name,
                "amount"        =>  $transaction->amount,
                "phone"         =>  $transaction->customer_mobile_number,
            ];

            $response = self::url($data);

            self::storeApiResponse($transaction, $response);

            if (isset($response->code) && isset($response->content->transactions->status) && $response->content->transactions->status === "failed") {
                // Insufficient API Wallet Balance Error
                $errorResponse = [
                    'error'     =>  'Insufficient Account Balance.',
                    'message'   =>  "An error occurred during bill payment request. Please try again later."
                ];
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            }

            if (isset($response->code) && isset($response->content->transactions->status) && $response->content->transactions->status === "delivered") {

                $amount = $transaction->amount;

                if (auth()->user()->isReseller()) {
                    $amount = CalculateDiscount::applyDiscount($amount, 'electricity');
                }

                self::$authUser->transaction($amount);

                $transaction->update([
                    'balance_after'     =>    self::$authUser->getAccountBalance(),
                    'status'            =>    true,
                    'token'             =>    $response->purchased_code,
                    'api_data_id'       =>    $response->content->transactions->transactionId,
                    // 'api_response'      =>    $response->response_description ?? NULL
                ]);

                BeneficiaryService::create($transaction->meter_number, 'electricity', $transaction);

                return ApiHelper::sendResponse($transaction, "Bill payment successful: ₦{$transaction->amount} {$transaction->meter_type_name} for ({$transaction->meter_number}).");
            }

            $errorResponse = [
                'error'     => 'Server Error',
                'message'   => "Opps! Unable to Perform transaction. Please try again later."
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            dd($th->getMessage());
            $errorResponse = [
                'error'     =>  'network connection error',
                'message'   =>  'Opps! Unable to make payment. Please check your network connection.'
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function validateMeterNumber($meterNumber, $discoId, $meterType)
    {
        try {

            $meterType =  $meterType == 1 ? 'prepaid' : 'postpaid';

            $data = [
                "billersCode"   =>  $meterNumber,
                "serviceID"     =>  $discoId,
                "type"          =>  $meterType
            ];

            $url = Str::replace('/pay', '', self::getUrl()) . self::VERIFY_NUMBER;

            $response = Http::withHeaders(self::headers())->post($url, $data);

            $response = $response->object();

            if (isset($response->code) && isset($response->content->Customer_Name)) {
                $responseData = [
                    'name'     => $response->content->Customer_Name,
                    'address'  => $response->content->Address,
                ];
                return ApiHelper::sendResponse($responseData, 'Meter Number validated. Proceed to make payment.');
            }

            $errorResponse = [
                'error'   =>  'Meter number error',
                'message' =>   'Invalid meter number. Provide a valid meter number.',
            ];

            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ApiHelper::sendError("network connection error", "Opps! Unable to validate number. Please check your network connection.");
        }
    }

    public static function cable($cableId, $cablePlan, $iucNumber, $customer)
    {

        try {

            $vendor = self::$vendor;
            $cable = Cable::whereVendorId($vendor->id)->whereCableId($cableId)->first();
            $cable_plan = CablePlan::whereVendorId($vendor->id)->whereCablePlanId($cablePlan)->first();

            $verifyAccountBalance = self::verifyAccountBalance($cable_plan->amount);
            if (!$verifyAccountBalance->status) {
                return ApiHelper::sendError($verifyAccountBalance->error, $verifyAccountBalance->message);
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
                'balance_after'       =>  Auth::user()->account_balance
            ]);

            $data = [
                "request_id"        =>  $transaction->transaction_id,
                "serviceID"         =>  $transaction->cable_id,
                "billersCode"       =>  $transaction->smart_card_number,
                "variation_code"    =>  $transaction->cable_plan_id,
                "amount"            =>  $transaction->amount,
                "phone"             =>  "08020536913",
                "subscription_type" => "change"
            ];

            $response = self::url($data);

            self::storeApiResponse($transaction, $response);

            if (isset($response->code) && isset($response->content->transactions->status) && $response->content->transactions->status === "failed") {
                // Insufficient API Wallet Balance Error
                $errorResponse = [
                    'error'     =>  'Insufficient Account Balance.',
                    'message'   =>  "An error occurred during cable payment request. Please try again later."
                ];
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            }

            if (isset($response->code) && isset($response->content->transactions->status) && $response->content->transactions->status === "delivered") {

                $amount = $transaction->amount;

                if (auth()->user()->isReseller()) {
                    $amount = CalculateDiscount::applyDiscount($amount, 'electricity');
                }

                self::$authUser->transaction($amount);

                $transaction->update([
                    'balance_after'     =>    self::$authUser->getAccountBalance(),
                    'status'            =>    true,
                    'api_data_id'       =>    $response->content->transactions->transactionId,
                    // 'api_response'      =>    $response->response_description ?? NULL
                ]);

                BeneficiaryService::create($transaction->smart_card_number, 'cable', $transaction);

                return ApiHelper::sendResponse($transaction, "Cable subscription successful: {$transaction->cable_plan_name} for ₦{$transaction->amount} on {$transaction->customer_name} ({$transaction->smart_card_number}).");
            }


            $errorResponse = [
                'error'     => 'Server Error',
                'message'   => "Opps! Unable to Perform transaction. Please try again later."
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error'     =>  'network connection error',
                'message'   =>  'Opps! Unable to payment payment. Please check your network connection.'
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function resultChecker($exam, $quantity)
    {
        try {

            if ( (int) $quantity > 5 ) {
                $errorResponse = [
                    'error'     => [],
                    'message'   => "No. of pins out of range allowed. Kindly note that valid values are 1,2,3,4,5"
                ];
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            }
    
            $resultCheckerModel = ResultChecker::where('vendor_id', self::$vendor->id)->where('name', $exam)->first();
            $amount = ($quantity * $resultCheckerModel->amount);
    
            $verifyAccountBalance = self::verifyAccountBalance($amount);
            if (!$verifyAccountBalance->status) {
                return ApiHelper::sendError($verifyAccountBalance->error, $verifyAccountBalance->message);
            }
    
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

            self::storeApiResponse($transaction, $response);
            
            if (isset($response->content->transactions) && $response->content->transactions->status === "delivered") {
                foreach ($response->cards as $card) {
                    $transaction->result_checker_pins()->create([
                        'serial' => $card->Serial,
                        'pin' => $card->Pin
                    ]);
                }
                self::$authUser->transaction($amount);
                $transaction->update([
                    'balance_after'     =>    self::$authUser->getAccountBalance(),
                    'api_data_id'       =>    $response->content->transactions->transactionId,
                    // 'api_response'      =>    $response->purchased_code,
                    'status'            =>    true,
                ]);
                return ApiHelper::sendResponse($transaction, "Result Checker PIN purchase successful: {$transaction->exam_name} ($transaction->quantity QTY) ₦{$amount}.");
            }

            $errorResponse = [
                'error'     => 'Server Error',
                'message'   => "Opps! Unable to Perform result checker PIN. Please try again later."
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error'     => $th->getMessage(),
                'message'   => "Opps! Unable to perform result checker PIN payment. Please check your network connection."
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function validateIUCNumber($iucNumber, $cableName)
    {
        try {

            $cable = Cable::whereVendorId(self::$vendor->id)->whereCableId($cableName)->first()?->cable_id;

            $vendor = self::$vendor;

            $data = [
                "billersCode"   =>  $iucNumber,
                "serviceID"     =>  $cable,
            ];


            $url = Str::replace('/pay', '', self::getUrl()) . self::VERIFY_NUMBER;

            $response = Http::withHeaders(self::headers())->post($url, $data);

            $response = $response->object();
            
            if (isset($response->code) && isset($response->content->Customer_Name)) {
                $responseData = [
                    'name'     => $response->content->Customer_Name,
                ];
                return ApiHelper::sendResponse($responseData, 'IUC validated. Proceed to make payment.');
            }

            $errorResponse = [
                'error'     =>   'IUC/SMARTCARD error',
                'message'   =>   'Invalid IUC/SMARTCARD. Please provide a valid IUC/SMARTCARD.',
            ];

            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ApiHelper::sendError("network connection error", "Opps! Unable to validate IUC number. Please check your network connection.");
        }
    }

    protected static function verifyAccountBalance($amount)
    {
        if (!self::$authUser->verifyAccountBalance($amount)) {
            $errorResponse = [
                'status'    =>  false,
                'error'     =>  'Insufficient Account Balance.',
                'message'   =>  "You need at least ₦{$amount} to purchase this plan. Please fund your wallet to continue."
            ];
            return (object) $errorResponse;
        }

        return (object) [
            'status'    =>  true
        ];
    }

    public static function getUrl()
    {
        if (app()->environment() == 'production') {
            return static::$vendor->api;
        }

        return static::TEST;
    }

    public static function getDataPlans($networkId)
    {
        try {

            $network = DataNetwork::find($networkId);
            $serviceIds = [];

            if ($network->name === "MTN") $serviceIds [] = self::MTN_CORPORATE;
            if ($network->name === "AIRTEL") $serviceIds [] = self::AIRTEL_CORPORATE;
            if ($network->name === "GLO") $serviceIds [] = self::GLO_CORPORATE;
            if ($network->name === "GLO") $serviceIds [] = self::GLO_SME;
            if ($network->name === "9MOBILE") $serviceIds [] = self::ETISALAT_CORPORATE;
            if ($network->name === "9MOBILE") $serviceIds [] = self::ETISALAT_SME;

            foreach ($serviceIds as $serviceId) {
                $url = Str::remove('pay', self::getUrl()) . "service-variations?serviceID=" . $serviceId;

                $response = Http::get($url);
                $response = $response->object();

                if (isset($response->response_description) && $response->response_description === "000") {

                    foreach ($response->content->variations as $dataPlan) {

                        $plan = DataPlan::where(['vendor_id' => self::$vendor->id, 'data_id' => $dataPlan->variation_code])->first();
                        
                        $size = "";
                        $string = $dataPlan->name;

                        if (strpos($string, 'Xtra') !== false) {
                            if (preg_match('/N([0-9,]+)/', $string, $matches)) {
                                $size =  $matches[0] . "\n";
                            }
                        }
                    
                        if (preg_match('/([0-9.]+ ?(?:TB|MB|mb|GB))/', $string, $matches)) {
                            $size =  $matches[0] . "\n";
                        }
        
                        if ($plan) {
                            $plan->update([
                                'live_amount'   => $dataPlan->variation_amount,
                                'live_size'     => $size,
                                'live_validity' => $dataPlan->name,
                            ]);
                        }
                    }
                }
            }

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public static function getCablePlans($cableId)
    {
        try {
            $cable = Cable::find($cableId);

            $serviceId = Str::lower($cable->cable_name);

            $url = Str::remove('pay', self::getUrl()) . "service-variations?serviceID=" . $serviceId;

            $response = Http::get($url);
            $response = $response->object();

            if (isset($response->response_description) && $response->response_description === "000") {

                foreach ($response->content->variations as $cablePlan) {
                    $plan = CablePlan::where(['vendor_id' => self::$vendor->id, 'cable_plan_id' => $cablePlan->variation_code])->first();
                    
                    if ($plan) {
                        $plan->update([
                            'live_amount'   => $cablePlan->variation_amount,
                            'live_package'  => $cablePlan->name,
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public static function getEducationPins()
    {
        try {
            $exams = ResultChecker::where('status', true)->get();

            foreach ($exams as $exam) {
                
                $serviceId = Str::lower($exam->name);
    
                $url = Str::remove('pay', self::getUrl()) . "service-variations?serviceID=" . $serviceId;
    
                $response = Http::get($url);
                $response = $response->object();
   
                if (isset($response->response_description) && $response->response_description === "000" && $response->content->serviceID === $serviceId) {
    
                    foreach ($response->content->variations as $variation) {
                        $exam->update([
                            'live_amount'   => $variation->variation_amount,
                        ]);
                    }
                }
            }

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public static function storeApiResponse($transaction, $response)
    {
        $transaction->update([
            'api_response' =>  json_encode($response)
        ]);

        return true;
    }
}
