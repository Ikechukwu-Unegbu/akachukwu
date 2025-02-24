<?php

namespace App\Services\Money;

use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use App\Models\PalmPayTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Idempotency\IdempotencyCheck;
use App\Services\Account\AccountBalanceService;

class PalmPayService
{
    protected CONST TEST_URL             = "https://open-gw-daily.palmpay-inc.com/";
    protected CONST PRODUCTION_URL       = "https://open-gw-prod.palmpay-inc.com/";
    protected CONST QUERY_ACCOUNT_URL    = "api/v2/payment/merchant/payout/queryBankAccount";
    protected CONST BANK_TRANSFER_URL    = "api/v2/merchant/payment/payout";
    protected CONST VIRTUAL_ACCOUNT_URL  = "api/v2/virtual/account/label/create";
    protected CONST ORDER_STATUS_UNPAID  = "unpaid";
    protected CONST ORDER_STATUS_PAYING  = "paying";
    protected CONST ORDER_STATUS_SUCCESS = "success";
    protected CONST ORDER_STATUS_FAIL    = "fail";
    protected CONST ORDER_CLOSE_FAIL     = "close";

    protected CONST BANK_CODE = 100033;
    protected CONST BANK_NAME = 'PalmPay';

    protected static function getUrl()
    {
        if (app()->environment() === 'production') 
            return self::PRODUCTION_URL;

        return self::TEST_URL;
    }

    protected static function header(string $signatures)
    {
        return [
            "Accept"        =>  "application/json",
            "Content-Type"  =>  "application/json",
            "Authorization" =>  "Bearer " . config('palmpay.app_id'),
            "Signature"     =>  $signatures,
            "CountryCode"   =>  config('palmpay.country_code'),
        ];
    }

    protected static function processEndpoint(string $url, array $payload)
    {
        try {

            $signatures  = self::generateSign($payload, config('palmpay.private_key'));

            $response = Http::withHeaders(self::header($signatures))->post(self::getUrl(). $url, $payload);
            
            return ($response->ok()) ? $response->object() : null;

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public static function queryBankAccount($bankCode, $accountNo)
    {        
        try {

            $data = [
                "requestTime" =>  round(microtime(true) * 1000),
                "version"     =>  "V1.1",
                "nonceStr"    =>  Str::random(40),
                "bankCode"    =>  $bankCode,
                "bankAccNo"   =>  $accountNo,
            ];

            $response = self::processEndpoint(self::QUERY_ACCOUNT_URL, $data);
          
            if (isset($response->data) && isset($response->data->Status)) {
                if ($response->data->Status === 'Success') {
                    return ApiHelper::sendResponse((array) $response->data, "Account verification successful."); 
                }
                if ($response->data->Status === 'Failed') {
                    return ApiHelper::sendError($response->data->errorMessage, "Account verification failed. Please check the details or try again later.");
                }
            }
           
            self::causer($response);            
            return ApiHelper::sendError([], "Unable to verify account at this time. Please check the details and try again later.");

        } catch (\Throwable $th) {
            self::causer($th->getMessage());     
            return ApiHelper::sendError("Server Error!", "An error occurred while verifying the account. Please try again later.");
        }
    }

    public static function processBankTransfer($accountName, $accountNo, $bankCode, $bankId, $amount, $fee, $remark, $userId)
    {
        try {
            DB::beginTransaction();
            /** Lock the user record to prevent double spending */
            $user = User::where('id', $userId)->lockForUpdate()->firstOrFail();            
            if ($user->account_balance < $amount)
                return ApiHelper::sendError('Insufficient balance', "Insufficient balance in your wallet to complete this transaction. Please top up and try again");
            
            /** Perform Wallet Deduction from the user's balance if they have enough funds */
            $balance_before = $user->account_balance;
            $user->decrement('account_balance', $amount);
            $balance_after = $user->account_balance;

            /** Check for duplicate transactions using IdempotencyCheck */
            if (self::initiateIdempotencyCheck($userId, $accountName, $accountNo, $bankCode)) 
                return ApiHelper::sendError([], "Transaction is already pending or recently completed. Please wait!");

            /**  Handle duplicate transactions */
            if (self::initiateLimiter($userId))
                return ApiHelper::sendError([], "Please Wait a moment. Last transaction still processing.");

            /** Create Transaction */
            $transactionData = [
                'user_id'          =>  $userId,
                'amount'           =>  $amount,
                'fee'              =>  $fee,
                'account_name'     =>  $accountName,
                'account_number'   =>  $accountNo,
                'bank_code'        =>  $bankCode,
                'bank_id'          =>  $bankId,
                'remark'           =>  $remark,
                'currency'         =>  config('palmpay.country_code'),
                'status'           =>  0,
                'transfer_status'  => self::ORDER_STATUS_UNPAID,
                'api_status'       => 'processing',
                'balance_before'   =>  $balance_before,
                'balance_after'    =>  $balance_after
            ];

            $transaction = PalmPayTransaction::create($transactionData);

            /** Prepare API Payload */
            $payload = [
                "requestTime"       => round(microtime(true) * 1000),
                "version"           => "V1.1",
                "nonceStr"          => $transaction->transaction_id,
                "orderId"           => $transaction->reference_id,
                "payeeName"         => $transaction->account_name,
                "payeeBankCode"     => $transaction->bank_code,
                "payeeBankAccNo"    => $transaction->account_number,
                "amount"            => intval($transaction->amount) * 10,
                "currency"          => config('palmpay.country_code'),
                "notifyUrl"         => route('webhook.palmpay'),
                "remark"            => $transaction->remark
            ];

            /** Store API Payload */
            $transaction->update(['meta' => $payload]);

            $response = self::processEndpoint(self::BANK_TRANSFER_URL, $payload);
            
            if (property_exists($response, 'data') && $response->data->message === 'success') {
                $transaction->update([
                    'status'          => $response->data->status,
                    'session_id'      => $response->data->sessionId,
                    'order_no'        => $response->data->orderNo,
                    'transfer_status' => self::ORDER_STATUS_PAYING,
                    'api_response'    => json_encode($response),
                    'api_status'      => 'successful'
                ]);

                DB::commit();
                return ApiHelper::sendResponse((array) $response->data, "Bank transfer successfully initiated.");
            }

            DB::commit();
            self::causer($response, 'Transaction Failed');
            return ApiHelper::sendError([], "Unable to perform bank transfer at this time. Please try again later.");
        } catch (\Throwable $th) {
            DB::rollBack();
            self::causer($th->getMessage(), 'Bank Transfer');
            return ApiHelper::sendError("Server Error!", "An error occurred while processing the transaction. Please try again later.");
        }
    }

    public static function webhook(Request $request)
    {
        // Verify the webhook signature
        $webhook = [
            'ip'      => $request->ip(),
            'time'    => date('H:i:s'),
            'date'    => date('d-m-Y'),
            'payload' => $request->all(),
            'headers' => $request->headers->all()
        ];

        self::storePayload($webhook);
        return;
    }

    public static function storePayload($payload)
    {
        $filename = 'palmpay-payload.json';
        $payloadString = json_encode($payload, JSON_PRETTY_PRINT);
    
        if (Storage::disk('webhooks')->exists($filename)) {

            $existingContent = Storage::disk('webhooks')->get($filename);
    
            $existingContent = rtrim($existingContent, "\n]");
    
            if (strlen($existingContent) > 1) {
                $existingContent .= ",\n";
            }

            $newContent = $existingContent . $payloadString . "\n]";
            Storage::disk('webhooks')->put($filename, $newContent);
        } else {
            $newContent = "[\n" . $payloadString . "\n]";
            Storage::disk('webhooks')->put($filename, $newContent);
        }
    }

    public static function createSpecificVirtualAccount($user, $accountId=null)
    {
        try {           
            if (!empty($user->nin)) {
                $kycType = 'nin';
                $kyc = $user->nin;
            } elseif (!empty($user->bvn)) {
                $kycType = 'bvn';
                $kyc = $user->bvn;
            }

            ## Example Payload
            // $payload = ["requestTime" => round(microtime(true) * 1000), "identityType" => "personal", "licenseNumber" => "dasd141234114123", "virtualAccountName" => "PPTV2", "version" => "V2.0", "customerName"  => "palmpayTester", "email" => "2222@palmpay.com", "nonceStr" => Str::random(40), "accountReference" => Str::random(60)];

            $payload = [
                "requestTime"          =>   round(microtime(true) * 1000),
                "identityType"         =>   "personal",
                "licenseNumber"        =>   $kyc,
                "virtualAccountName"   =>   $user->name,   
                "version"              =>   "V2.0",
                "customerName"         =>   $user->name,
                "email"                =>   $user->email,
                "nonceStr"             =>   self::generateVirtualAccountReference(15),
                "accountReference"     =>   self::generateVirtualAccountReference(25)
            ];

            $response = self::processEndpoint(self::VIRTUAL_ACCOUNT_URL, $payload);

            if (isset($response->data) && isset($response->status) && $response->status) {
                VirtualAccount::create([
                    "reference"      => $response->data->accountReference,
                    "bank_code"      => self::BANK_CODE,
                    "bank_name"      => self::BANK_NAME,
                    "account_name"   => $response->data->virtualAccountName,
                    "account_number" => $response->data->virtualAccountNo,
                    "account_type"   => $response->data->identityType,
                    "status"         => 'ACTIVE',
                    "user_id"        => $user->id,
                    "reservation_reference" => $response->data->appId,
                    "payment_id"     => PaymentGateway::where('name', 'Palmpay')->first()?->id
                ]);
                
                return ApiHelper::sendResponse([], "Virtual Account Created Succeefully.");
            }

            self::causer($response, 'Palmpay Virtul Account');
            return ApiHelper::sendError('API Error', 'Unable to create virtual account. Please try again later.');

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            self::causer($th->getMessage(), 'Palmpay Virtul Account');
            return ApiHelper::sendError([], "Opps! Unable to create static account. Please check your network connection.");
        }    
    }

    protected static function generateSign($params, $privateKey) 
    {
        // Step 1: Create the parameter string
        ksort($params); // Sort parameters by key
        $strA = '';
        foreach ($params as $key => $value) {
            if ($value !== null && $value !== '') {
                $strA .= $key . '=' . trim($value) . '&';
            }
        }
        $strA = rtrim($strA, '&'); // Remove the trailing '&'
  
        // Step 2: Generate MD5 hash and convert to uppercase
        $md5Str = strtoupper(md5($strA));
    
        // Step 3: Sign the MD5 hash with SHA1 and RSA
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" . chunk_split($privateKey, 64, "\n") . "-----END RSA PRIVATE KEY-----";
        openssl_sign($md5Str, $signature, $privateKey, OPENSSL_ALGO_SHA1);
        $sign = base64_encode($signature);
    
        return $sign;
    }
    
    protected static function verifySign($params, $publicKey, $sign) 
    {
         // Step 1: Create the parameter string
        ksort($params); // Sort parameters by key
        $strA = '';
        foreach ($params as $key => $value) {
            if ($value !== null && $value !== '') {
                $strA .= $key . '=' . trim($value) . '&';
            }
        }
        $strA = rtrim($strA, '&'); // Remove the trailing '&'

        // Step 2: Generate MD5 hash and convert to uppercase
        $md5Str = strtoupper(md5($strA));

        // Step 3: Verify the signature
        // Ensure the public key is in PEM format
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($publicKey, 64, "\n") . "-----END PUBLIC KEY-----";
        $signature = base64_decode($sign);
        $result = openssl_verify($md5Str, $signature, $publicKey, OPENSSL_ALGO_SHA1);

        return $result === 1;
    }

    protected static function causer($error, $attempt = null) : void
    {
        $log =  [
            'username' => Auth::user()->username, 
            'Attempts' => ($attempt) ? $attempt : 'Account No. Verification (PalmPay)', 
            'Error' => $error, 
            'dateTime' => date('d-M-Y H:i:s A')
        ];
        Log::error($log);
    }

    protected static function initiateLimiter($userId) : bool
    {        
        $rateLimitKey = "palmpay-bank-transfer-{$userId}";

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);           
            return true;
        }
    
        RateLimiter::hit($rateLimitKey, 60);

        return false;
    }

    protected static function initiateIdempotencyCheck($userId, $accountName, $accountNo, $bankCode)
    {
        $duplicateTransaction = IdempotencyCheck::checkDuplicateTransaction(
            PalmPayTransaction::class, 
            [
                'user_id'        => $userId, 
                'account_name'   => $accountName, 
                'account_number' => $accountNo,
                'bank_code'      => $bankCode,
            ],
            'api_status',
            ['successful', 'failed', 'pending']
        );
  
        return $duplicateTransaction;
    }

    public static function generateVirtualAccountReference($length = 15): string
    {
        $referenceId = Str::random($length);
        $referenceId = preg_replace('/[^a-zA-Z0-9_-]/', '', $referenceId);
        return $referenceId;
    }
}
