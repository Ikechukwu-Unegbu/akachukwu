<?php
namespace App\Services\Money;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\User;
use App\Helpers\ApiHelper;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MoneyTransfer;
use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use App\Helpers\GeneralHelpers;
use App\Models\PalmPayTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Idempotency\IdempotencyCheck;
use App\Services\Account\AccountBalanceService;

class BasePalmPayService
{
    protected CONST TEST_URL             = "https://open-gw-daily.palmpay-inc.com/";
    protected CONST PRODUCTION_URL       = "https://open-gw-prod.palmpay-inc.com/";
    protected CONST QUERY_ACCOUNT_URL    = "api/v2/payment/merchant/payout/queryBankAccount";
    protected CONST BANK_TRANSFER_URL    = "api/v2/merchant/payment/payout";
    protected CONST VIRTUAL_ACCOUNT_URL  = "api/v2/virtual/account/label/create";
    protected CONST ORDER_STATUS_UNPAID  = "pending";
    protected CONST ORDER_STATUS_PAYING  = "processing";
    protected CONST ORDER_STATUS_SUCCESS = "successful";
    protected CONST ORDER_STATUS_FAIL    = "failed";

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

    protected static function generateUniqueReferenceId(): string
    {
        return Str::slug(date('YmdHi').'-palmpay-'.Str::random(10).Str::random(4));
    }

    protected static function generateUniqueTransactionId(): string
    {
        $vendorCode = 'VST';
        $transactionNumber = Auth::id();
        $timestamp = date('YmdHis');
        $randomDigits = Str::random(6);

        return "{$vendorCode}|{$transactionNumber}|{$timestamp}|{$randomDigits}";
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
            'username' => Auth::user()->username ?? '',
            'Attempts' => ($attempt) ? $attempt : 'Account No. Verification (PalmPay)',
            'Error' => $error,
            'dateTime' => date('d-M-Y H:i:s A')
        ];
        Log::error($log);
    }

    protected static function initiateLimiter($userId) : bool
    {
        $rateLimitKey = "palmpay-{$userId}";

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            RateLimiter::availableIn($rateLimitKey);
            return true;
        }

        RateLimiter::hit($rateLimitKey, 60);

        return false;
    }

    protected static function initiateIdempotencyCheck($userId, $accountNo, $bankCode)
    {
        $duplicateTransaction = IdempotencyCheck::checkDuplicateTransaction(
            MoneyTransfer::class,
            [
                'user_id'        => $userId,
                'account_number' => $accountNo,
                'bank_code'      => $bankCode,
            ],
            'transfer_status',
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

    protected static function validateTransaction(User $user, float $totalAmount)
    {
        if (!$user->isKycDone()) {
            return ApiHelper::sendError([], "To continue enjoying our services, please complete your KYC by providing your BVN or NIN.");
        }

        if (!GeneralHelpers::minimumTransaction($totalAmount)) {
            return ApiHelper::sendError([], "The amount is below the minimum transfer limit.");
        }

        $singleLimit = GeneralHelpers::singleTransactionLimit($value, $user->id);
        if (!$singleLimit->status) {
            $fail("The maximum single transfer limit is ₦" . number_format($singleLimit->limit, 2));
        }

        if (!GeneralHelpers::dailyTransactionLimit(MoneyTransfer::class, $totalAmount, $user->id)) {
            return ApiHelper::sendError([], "You have exceeded your daily transaction limit.");
        }

        if ($user->account_balance < $totalAmount) {
            return ApiHelper::sendError('Insufficient balance', "Insufficient balance in your wallet to complete this transaction. Please top up and try again");
        }

        return null;
    }

    protected static function checkIfAccountExists($user): bool
    {
        return $user->virtualAccounts->where('bank_code', self::BANK_CODE)->isNotEmpty();
    }

    public static function createSpecificVirtualAccount($user, $accountId = null)
    {
        try {

            if (self::checkIfAccountExists($user)) {
                return ApiHelper::sendError(['Account already exists'], "A virtual account with this bank already exists for the user.");
            }

            if (!empty($user->nin)) {
                $kycType = 'nin';
                $kyc = $user->nin;
                $identityType = 'personal_nin';
            } elseif (!empty($user->bvn)) {
                $kycType = 'bvn';
                $kyc = $user->bvn;
                $identityType = 'personal';
            } else {
                return false;
            }

            ## Example Payload
            // $payload = ["requestTime" => round(microtime(true) * 1000), "identityType" => "personal", "licenseNumber" => "dasd141234114123", "virtualAccountName" => "PPTV2", "version" => "V2.0", "customerName"  => "palmpayTester", "email" => "2222@palmpay.com", "nonceStr" => Str::random(40), "accountReference" => Str::random(60)];

            $payload = [
                "requestTime"          =>   round(microtime(true) * 1000),
                "identityType"         =>   $identityType,
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

                return ApiHelper::sendResponse([], "Virtual Account Created Successfully.");
            }

            self::causer($response, 'Palmpay Virtual Account');
            return ApiHelper::sendError('API Error', 'Unable to create virtual account. Please try again later.');

        } catch (\Throwable $th) {
            self::causer($th->getMessage(), 'Palmpay Virtual Account');
            return ApiHelper::sendError([], "Opps! Unable to create static account. Please check your network connection.");
        }
    }
}
