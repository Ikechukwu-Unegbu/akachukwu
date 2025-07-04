<?php

namespace App\Services\Vendor;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Vendor;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Utility\Cable;
use App\Helpers\GeneralHelpers;
use App\Models\Data\DataNetwork;
use App\Models\Utility\CablePlan;
use Illuminate\Support\Facades\DB;
use App\Helpers\Admin\VendorHelper;
use App\Models\Utility\Electricity;
use App\Services\CalculateDiscount;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Education\ResultChecker;
use App\Models\Utility\CableTransaction;
use App\Services\Airtime\AirtimeService;
use App\Models\Utility\AirtimeTransaction;
use App\Services\Referrals\ReferralService;
use App\Services\ScheduledTransactionService;
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
        try {

            $response = Http::withHeaders(self::headers())->get($url);
            $response = $response->object();

            if (isset($response->code) && $response->code) {
                return ApiHelper::sendResponse(number_format($response->contents->balance, 2), 'Wallet Balance Fetched Successfully');
            }

            return ApiHelper::sendError('', '');

        } catch (\Throwable $th) {

        }

    }

    public static function airtime($networkId, $amount, $mobileNumber, $isScheduled = false, $scheduledPayload, $initialRun = false, $hasTransaction)
    {
        try {

            $checkLimit = AirtimeService::checkAirtimeLimit($amount);
            if ($checkLimit !== true) {
                return $checkLimit;
            }

            return DB::transaction(function () use ($networkId, $amount, $mobileNumber, $isScheduled, $scheduledPayload, $initialRun, $hasTransaction) {


                // Lock user's account balance to prevent concurrent modifications
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();


                GeneralHelpers::randomDelay();

                // Retrieve network details
                $network = DataNetwork::whereVendorId(self::$vendor->id)
                    ->whereNetworkId($networkId)
                    ->firstOrFail();

                $discount = $network->airtime_discount;

                // Create the airtime transaction record
                if (!$hasTransaction) {
                    $transaction = AirtimeTransaction::create([
                        'user_id'           => Auth::id(),
                        'vendor_id'         => self::$vendor->id,
                        'network_id'        => $network->network_id,
                        'network_name'      => $network->name,
                        'amount'            => $amount,
                        'mobile_number'     => $mobileNumber,
                        'discount'          => $discount,
                    ]);
                }

                if ($hasTransaction) $transaction = $hasTransaction;

                // Apply reseller discount if applicable
                $discountedAmount = $amount;
                if (auth()->user()->isReseller()) {
                    $discountedAmount = CalculateDiscount::applyDiscount($discountedAmount, 'airtime');
                }

                $discountedAmount = CalculateDiscount::calculate($discountedAmount, $discount);

                if (!$initialRun) {
                    // Deduct the amount from the user's account balance
                    $user->account_balance -= $discountedAmount;
                    $user->save();
                }

                if ($isScheduled) {
                    $transaction->pending();
                    $scheduledPayload['type'] = 'airtime';
                    $schedulerService = ScheduledTransactionService::initiateScheduler($transaction, $scheduledPayload);

                    if (isset($schedulerService['status']) && $schedulerService['status']) {
                        return ApiHelper::sendResponse($transaction, $schedulerService['message']);
                    }

                    $errorResponse = [
                        'error' => 'Scheduler Failed',
                        'message' => "An error occurred during scheduling Airtime request. Please try again later."
                    ];

                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }



                // Prepare data for the API request
                $data = [
                    'request_id'   => $transaction->transaction_id,
                    'serviceID'    => Str::lower($network->name),
                    'amount'       => $amount,
                    'phone'        => $mobileNumber,
                ];

                // Call the external API
                $response = static::url($data);

                // Store the API response
                self::storeApiResponse($transaction, $response);



                // Handle API response
                if (isset($response->code) && isset($response->content->transactions->status)) {
                    $status = $response->content->transactions->status;

                    if ($status === 'failed') {
                        self::$authUser->initiatePending($discountedAmount, $transaction);

                        $errorResponse = [
                            'error'   => 'API Error',
                            'message' => 'An error occurred during the Airtime request. Please try again later.',
                        ];
                        return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                    }

                    if ($status === 'delivered') {
                        // Update the transaction on success
                        $transaction->update([
                            // 'balance_after'     => $user->account_balance,
                            'api_data_id'       => $response->content->transactions->transactionId,
                            'amount'            => $discountedAmount,
                        ]);

                        self::$authUser->initiateSuccess($discountedAmount, $transaction);

                        // Record beneficiary information
                        BeneficiaryService::create($transaction->mobile_number, 'airtime', $transaction);

                        return ApiHelper::sendResponse($transaction, "Airtime purchase successful: ₦{$discountedAmount} {$network->name} airtime added to {$mobileNumber}.");
                    }
                }

                // Handle unexpected responses or server errors
                $errorResponse = [
                    'error'   => 'Server Error',
                    'message' => 'Oops! Unable to perform the transaction. Please try again later.',
                ];
                self::$authUser->initiatePending($discountedAmount, $transaction);
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            // Log and handle any exceptions
            Log::error($th->getMessage());

            $errorResponse = [
                'error'   => 'Network Connection Error',
                'message' => 'Oops! Unable to perform airtime payment. Please check your network connection.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function data($networkId, $typeId, $dataId, $mobileNumber, $isScheduled = false, $scheduledPayload, $initialRun = false, $hasTransaction)
    {
        try {
            return DB::transaction(function () use ($networkId, $typeId, $dataId, $mobileNumber, $isScheduled, $scheduledPayload, $initialRun, $hasTransaction) {
                // Lock user's account balance to prevent concurrent modifications
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

                // Retrieve network, plan, and type details
                $vendor = self::$vendor;
                $network = DataNetwork::whereVendorId($vendor->id)->whereNetworkId($networkId)->firstOrFail();
                $plan = DataPlan::whereVendorId($vendor->id)->whereNetworkId($network->network_id)->whereDataId($dataId)->firstOrFail();
                $type = DataType::whereVendorId($vendor->id)->whereNetworkId($network->network_id)->whereId($typeId)->firstOrFail();


                $discount = $network->data_discount;

                // Create the data transaction record
                if (!$hasTransaction) {
                    $transaction = DataTransaction::create([
                        'user_id'          => $user->id,
                        'vendor_id'        => $vendor->id,
                        'network_id'       => $network->network_id,
                        'type_id'          => $type->id,
                        'data_id'          => $plan->data_id,
                        'amount'           => $plan->amount,
                        'size'             => $plan->size,
                        'validity'         => $plan->validity,
                        'mobile_number'    => $mobileNumber,
                        // 'balance_before'   => $user->account_balance,
                        // 'balance_after'    => $user->account_balance - $plan->amount,
                        'plan_network'     => $network->name,
                        'plan_name'        => $plan->size,
                        'plan_amount'      => $plan->amount,
                        'discount'         => $discount,
                    ]);
                }

                if ($hasTransaction) $transaction = $hasTransaction;

                // Apply reseller discount if applicable
                $amount = $plan->amount;
                $discountedAmount = $amount;
                if (auth()->user()->isReseller()) {
                    $discountedAmount = CalculateDiscount::applyDiscount($discountedAmount, 'data');
                }
                $discountedAmount = CalculateDiscount::calculate($discountedAmount, $discount);

                if (!$initialRun) {
                    // Deduct the amount from the user's account balance
                    $user->account_balance -= $discountedAmount;
                    $user->save();
                }

                if ($isScheduled) {
                    $transaction->pending();
                    $scheduledPayload['type'] = 'data';
                    $schedulerService = ScheduledTransactionService::initiateScheduler($transaction, $scheduledPayload);
                    if (isset($schedulerService['status']) && $schedulerService['status']) {
                        return ApiHelper::sendResponse($transaction, $schedulerService['message']);
                    }

                    $errorResponse = [
                        'error' => 'Scheduler Failed',
                        'message' => "An error occurred during scheduling Data request. Please try again later."
                    ];

                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                // Prepare data for the API request
                $serviceId = ($type->dataNetwork->name === "MTN" && in_array($type->name, ["CORPORATE", "SME"])) ?
                            self::MTN_CORPORATE :
                            $plan->service_id;

                $data = [
                    'request_id'     => $transaction->transaction_id,
                    'serviceID'      => $serviceId,
                    'billersCode'    => $transaction->mobile_number,
                    'variation_code' => $transaction->data_id,
                    'amount'         => $transaction->amount,
                    'phone'          => $transaction->mobile_number,
                ];

                // Call the external API
                $response = self::url($data);

                // Store the API response
                self::storeApiResponse($transaction, $response);



                // Handle API response
                if (isset($response->code) && isset($response->content->transactions->status)) {
                    $status = $response->content->transactions->status;

                    if ($status === 'failed') {
                        self::$authUser->initiateRefund($user, $discountedAmount, $transaction);

                        $errorResponse = [
                            'error'   => 'API Error',
                            'message' => 'An error occurred during the Data request. Please try again later.',
                        ];
                        return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                    }

                    if ($status === 'delivered') {
                        // Update the transaction on success
                        $transaction->update([
                            // 'balance_after'   => $user->account_balance,
                            'plan_amount'     => $response->amount,
                            'api_data_id'     => $response->content->transactions->transactionId,
                        ]);

                        self::$authUser->initiateSuccess($discountedAmount, $transaction);

                        (new ReferralService)->payReferrerForData(Auth::user(), $plan, $type, $transaction);

                        // Record beneficiary information
                        BeneficiaryService::create($transaction->mobile_number, 'data', $transaction);

                        return ApiHelper::sendResponse($transaction, "Data purchase successful: {$network->name} {$plan->size} for ₦{$plan->amount} on {$mobileNumber}.");
                    }
                }

                // Handle unexpected responses or server errors
                $errorResponse = [
                    'error'   => 'Server Error',
                    'message' => 'Oops! Unable to perform the transaction. Please try again later.',
                ];
                self::$authUser->initiatePending($discountedAmount, $transaction);
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            // Log and handle any exceptions
            Log::error($th->getMessage());

            $errorResponse = [
                'error'   => 'Network Connection Error',
                'message' => 'Oops! Unable to make payment. Please check your network connection.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }
    public static function electricity($discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress)
    {
        try {
            return DB::transaction(function () use ($discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress) {
                // Validate the minimum payment amount
                if ($amount < 500) {
                    $errorResponse = [
                        'error'   => 'Minimum Account Error',
                        'message' => 'The minimum amount is ₦500.',
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                // Lock user's account balance to prevent concurrent modifications
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

                // Ensure the user's account balance is sufficient
                if ($user->account_balance < $amount) {
                    $errorResponse = [
                        'error'   => 'Insufficient Balance',
                        'message' => 'Your account balance is insufficient to complete this transaction.',
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                // Retrieve electricity vendor details
                $vendor = self::$vendor;
                $electricity = Electricity::whereVendorId($vendor->id)->whereDiscoId($discoId)->firstOrFail();
                $discount = $electricity->discount;

                // Create electricity transaction record
                $transaction = ElectricityTransaction::create([
                    'user_id'                   => $user->id,
                    'vendor_id'                 => $vendor->id,
                    'disco_id'                  => $electricity->disco_id,
                    'disco_name'                => $electricity->disco_name,
                    'meter_number'              => $meterNumber,
                    'meter_type_id'             => $meterType,
                    'meter_type_name'           => $meterType == 1 ? 'prepaid' : 'postpaid',
                    'amount'                    => $amount,
                    'customer_mobile_number'    => $customerMobile,
                    'customer_name'             => $customerName,
                    'customer_address'          => $customerAddress,
                    // 'balance_before'            => $user->account_balance,
                    // 'balance_after'             => $user->account_balance - $amount,
                    'discount'                  => $discount,
                ]);

                 // Apply reseller discount if applicable
                 $finalAmount = $amount;
                 if (auth()->user()->isReseller()) {
                     $finalAmount = CalculateDiscount::applyDiscount($finalAmount, 'electricity');
                 }
                 $finalAmount = CalculateDiscount::calculate($finalAmount, $discount);

                // Deduct the amount from the user's account balance
                $user->account_balance -= $finalAmount;
                $user->save();

                // Prepare data for the API request
                $data = [
                    "request_id"    => $transaction->transaction_id,
                    "serviceID"     => $transaction->disco_id,
                    "billersCode"   => $transaction->meter_number,
                    "variation_code" => $transaction->meter_type_name,
                    "amount"        => $transaction->amount,
                    "phone"         => $transaction->customer_mobile_number,
                ];

                // Call the external API
                $response = self::url($data);

                // Store the API response
                self::storeApiResponse($transaction, $response);

                // Handle API response
                if (isset($response->code) && isset($response->content->transactions->status)) {
                    $status = $response->content->transactions->status;

                    if ($status === 'failed') {
                        self::$authUser->initiateRefund($user, $finalAmount, $transaction);

                        $errorResponse = [
                            'error'   => 'API Error',
                            'message' => 'An error occurred during bill payment request. Please try again later.',
                        ];
                        return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                    }

                    if ($status === 'delivered') {
                        // Update the transaction on success
                        $transaction->update([
                            // 'balance_after' => $user->account_balance,
                            'status'        => true,
                            'token'         => VendorHelper::removeTokenPrefix($response->purchased_code),
                            'api_data_id'   => $response->content->transactions->transactionId,
                        ]);

                        self::$authUser->initiateSuccess($finalAmount, $transaction);

                        // Record beneficiary information
                        BeneficiaryService::create($transaction->meter_number, 'electricity', $transaction);

                        return ApiHelper::sendResponse($transaction, "Bill payment successful: ₦{$transaction->amount} {$transaction->meter_type_name} for ({$transaction->meter_number}).");
                    }
                }

                // Handle unexpected responses or server errors
                $errorResponse = [
                    'error'   => 'Server Error',
                    'message' => 'Oops! Unable to perform the transaction. Please try again later.',
                ];
                self::$authUser->initiatePending($finalAmount, $transaction);
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            // Log and handle any exceptions
            Log::error($th->getMessage());

            $errorResponse = [
                'error'   => 'Network Connection Error',
                'message' => 'Oops! Unable to make payment. Please check your network connection.',
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
            return DB::transaction(function () use ($cableId, $cablePlan, $iucNumber, $customer) {
                // Fetch the vendor and cable details
                $vendor = self::$vendor;
                $cable = Cable::whereVendorId($vendor->id)->whereCableId($cableId)->firstOrFail();
                $cable_plan = CablePlan::whereVendorId($vendor->id)->whereCablePlanId($cablePlan)->firstOrFail();

                // Lock user's account balance to prevent concurrent modifications
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

                // Check if user account balance is sufficient
                if ($user->account_balance < $cable_plan->amount) {
                    $errorResponse = [
                        'error'   => 'Insufficient Balance',
                        'message' => 'Your account balance is insufficient to complete this transaction.',
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                $discount = $cable->discount;

                // Create the cable transaction record
                $transaction = CableTransaction::create([
                    'user_id'             => $user->id,
                    'vendor_id'           => $vendor->id,
                    'cable_name'          => $cable->cable_name,
                    'cable_id'            => $cable->cable_id,
                    'cable_plan_name'     => $cable_plan->package,
                    'cable_plan_id'       => $cable_plan->cable_plan_id,
                    'smart_card_number'   => $iucNumber,
                    'customer_name'       => $customer,
                    'amount'              => $cable_plan->amount,
                    // 'balance_before'      => $user->account_balance,
                    // 'balance_after'       => $user->account_balance - $cable_plan->amount,
                    'discount'            => $discount,
                ]);

                // Apply reseller discount if applicable
                $finalAmount = $cable_plan->amount;
                if (auth()->user()->isReseller()) {
                    $finalAmount = CalculateDiscount::applyDiscount($finalAmount, 'cable');
                }
                $finalAmount = CalculateDiscount::calculate($finalAmount, $discount);

                // Deduct the amount from the user's account balance
                $user->account_balance -= $finalAmount;
                $user->save();

                // Prepare data for the API request
                $data = [
                    "request_id"        => $transaction->transaction_id,
                    "serviceID"         => $transaction->cable_id,
                    "billersCode"       => $transaction->smart_card_number,
                    "variation_code"    => $transaction->cable_plan_id,
                    "amount"            => $transaction->amount,
                    "phone"             => "08020536913",
                    "subscription_type" => "change",
                ];

                // Call the external API
                $response = self::url($data);

                // Store the API response
                self::storeApiResponse($transaction, $response);


                // Handle API response
                if (isset($response->code) && isset($response->content->transactions->status)) {
                    $status = $response->content->transactions->status;

                    if ($status === 'failed') {
                        self::$authUser->initiateRefund($user, $finalAmount, $transaction);

                        $errorResponse = [
                            'error'   => 'API Error',
                            'message' => 'An error occurred during cable payment request. Please try again later.',
                        ];
                        return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                    }

                    if ($status === 'delivered') {
                        // Update the transaction on success
                        $transaction->update([
                            // 'balance_after' => $user->account_balance,
                            'status'        => true,
                            'api_data_id'   => $response->content->transactions->transactionId,
                        ]);

                        self::$authUser->initiateSuccess($finalAmount, $transaction);

                        // Record beneficiary information
                        BeneficiaryService::create($transaction->smart_card_number, 'cable', $transaction);

                        return ApiHelper::sendResponse($transaction, "Cable subscription successful: {$transaction->cable_plan_name} for ₦{$transaction->amount} on {$transaction->customer_name} ({$transaction->smart_card_number}).");
                    }
                }

                // Handle unexpected responses or server errors
                $errorResponse = [
                    'error'   => 'Server Error',
                    'message' => 'Oops! Unable to perform the transaction. Please try again later.',
                ];
                self::$authUser->initiatePending($finalAmount, $transaction);
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            // Log and handle exceptions
            Log::error($th->getMessage());

            $errorResponse = [
                'error'   => 'Network Connection Error',
                'message' => 'Oops! Unable to make payment. Please check your network connection.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }


    public static function resultChecker($exam, $quantity)
    {
        try {
            return DB::transaction(function () use ($exam, $quantity) {
                // Validate quantity
                if ((int)$quantity > 5) {
                    $errorResponse = [
                        'error'   => [],
                        'message' => "No. of pins out of range allowed. Kindly note that valid values are 1,2,3,4,5"
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                // Fetch the result checker model
                $resultCheckerModel = ResultChecker::where('vendor_id', self::$vendor->id)->where('name', $exam)->firstOrFail();
                $amount = ($quantity * $resultCheckerModel->amount);

                // Lock the user's account balance to prevent concurrent modifications
                $user = Auth::user();
                $user->lockForUpdate();

                // Verify if the user has enough balance
                if ($user->account_balance < $amount) {
                    $errorResponse = [
                        'error'   => 'Insufficient Balance',
                        'message' => 'Your account balance is insufficient to complete this transaction.'
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                // Create the result checker transaction record
                $transaction = ResultCheckerTransaction::create([
                    'vendor_id'         => self::$vendor->id,
                    'result_checker_id' => $resultCheckerModel->id,
                    'exam_name'         => $resultCheckerModel->name,
                    'quantity'          => $quantity,
                    'amount'            => $amount,
                    // 'balance_before'    => $user->account_balance,
                    // 'balance_after'     => $user->account_balance - $amount
                ]);

                // Deduct the amount from the user's account balance
                $user->account_balance -= $amount;
                $user->save();

                // Prepare data for the API request
                $data = [
                    "request_id"     => $transaction->reference_id,
                    "serviceID"      => Str::lower($resultCheckerModel->name),
                    "variation_code" => "waecdirect",
                    "quantity"       => $quantity,
                    "phone"          => "08020536913"
                ];

                // Call the external API
                $response = self::url($data);

                // Store the API response
                self::storeApiResponse($transaction, $response);

                // Handle the API response and check status
                if (isset($response->code) && isset($response->content->transactions->status)) {
                    $status = $response->content->transactions->status;

                    if ($status === 'failed') {
                        self::$authUser->initiatePending($amount, $transaction);

                        $errorResponse = [
                            'error'   => 'Insufficient Account Balance.',
                            'message' => "An error occurred during e-pin request. Please try again later."
                        ];
                        return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                    }

                    if ($status === 'delivered') {
                        // Save the result checker pins
                        foreach ($response->cards as $card) {
                            $transaction->result_checker_pins()->create([
                                'serial' => $card->Serial,
                                'pin'    => $card->Pin
                            ]);
                        }

                        // Update the transaction status after successful purchase
                        $transaction->update([
                            // 'balance_after' => $user->account_balance,
                            'api_data_id'   => $response->content->transactions->transactionId
                        ]);

                        self::$authUser->initiateSuccess($amount, $transaction);
                        return ApiHelper::sendResponse($transaction, "Result Checker PIN purchase successful: {$transaction->exam_name} ($transaction->quantity QTY) ₦{$amount}.");
                    }
                }

                // Handle unexpected responses or server errors
                $errorResponse = [
                    'error'   => 'Server Error',
                    'message' => "Opps! Unable to Perform result checker PIN. Please try again later."
                ];
                self::$authUser->initiatePending($amount, $transaction);
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            // Log and handle exceptions
            Log::error($th->getMessage());

            $errorResponse = [
                'error'   => $th->getMessage(),
                'message' => "Opps! Unable to perform result checker PIN payment. Please check your network connection."
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
