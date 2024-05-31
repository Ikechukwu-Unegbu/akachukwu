<?php

namespace App\Services\Payment;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use Illuminate\Support\Collection;
use App\Interfaces\Payment\Payment;
use App\Models\MoneyTransfer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Payment\MonnifyTransaction;
use App\Models\VirtualAccount;
use App\Models\User;
use App\Services\Account\AccountBalanceService;

class MonnifyService implements Payment
{

    public static $accountBalance;

    private CONST  LIVE = "https://api.monnify.com/";
    private CONST TEST = "https://sandbox.monnify.com/";

    public function isGatewayAvailable(): bool
    {
        return true;
    }

    public static function token()
    {
        try {

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Basic " . base64_encode(static::monnifyDetails('public_key') . ':' . static::monnifyDetails('key')),
            ])->post(self::getUrl() . 'api/v1/auth/login');

            $response = $response->object();

            if ($response->requestSuccessful) {
                return $response->responseBody->accessToken;
            }

            return false;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public function createPaymentIntent($amount, $redirectURL, $user, array $meta = null)
    {
        try {

            $transaction = MonnifyTransaction::create([
                'user_id'       =>  $user->id,
                'reference_id'  => $this->generateUniqueId(),
                'amount'        => $amount,
                'currency'      => config('app.currency', 'NGN'),
                'redirect_url'  => $redirectURL,
                'meta'          => json_encode($meta)
            ]);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'bearer ' . $this->token(),
            ])->post(self::getUrl() . 'api/v1/merchant/transactions/init-transaction', [
                'amount'                =>   $transaction->amount,
                'customerName'          =>   $user->name,
                'customerEmail'         =>   $user->email,
                'paymentReference'      =>   $transaction->reference_id,
                'paymentDescription'    =>   'Wallet Funding',
                'currencyCode'          =>   $transaction->currency,
                'contractCode'          =>   static::monnifyDetails('contract_code'),
                'redirectUrl'           =>   $redirectURL,
                'paymentMethods'        =>   ['CARD', 'ACCOUNT_TRANSFER'],
                'metadata'              =>   $meta,
            ]);

            if (!$response->ok()) {
                throw new Exception('Invalid Response From Payment Gateway');
            }

            $response = $response->object();

            $transaction->update(['trx_ref' => $response->responseBody->transactionReference]);

            return collect([
                'paymentLink' => $response->responseBody->checkoutUrl,
                'message' => $response->responseMessage,
                'status' => $response->requestSuccessful,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public static function moneyTransfer($amount, $bankCode, $bankName, $recipientAccount, $narration = null)
    {
        try {
            static::$accountBalance = new AccountBalanceService(Auth::user());

            // if (!self::$accountBalance->verifyAccountBalance($amount)) {
            //     return json_encode([
            //         'error' => 'Insufficient Account Balance.',
            //         'message' => "You need at least ₦{$amount} to make money transfer!",
            //     ]);
            // }

            $transaction = MoneyTransfer::create([
                'reference_id'  =>  self::generateMoneyTransferReference(),
                'amount'        =>  $amount,
                'narration'     =>  $narration,
                'bank_code'     =>  $bankCode,
                'bank_name'     =>  $bankName,
                'account_number' =>  $recipientAccount,
            ]);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'bearer ' . static::token(),
            ])->post(self::getUrl() . 'api/v2/disbursements/single', [
                'amount'                    =>  $transaction->amount,
                'reference'                 =>  $transaction->reference_id,
                'narration'                 =>  $transaction->narration,
                'destinationBankCode'       =>  $transaction->bank_code,
                'destinationAccountNumber'  =>  $transaction->account_number,
                'currency'                  =>  'NGN',
                'sourceAccountNumber'       =>  static::monnifyDetails('account_number'),
                'async'                     =>  true
            ]);

            // if (!$response->ok()) {
            //     throw new Exception('Invalid Response From Payment Gateway');
            // }

            return $response->object();

        } catch (\Exception $e) {
           // dd($e->getMessage());
        }
    }

    public function processPayment($request): bool
    {
        $transaction = MonnifyTransaction::where('reference_id', $request->paymentReference)->first();

        if ($transaction == null || !$transaction->exists()) {
            return false;
        }

        if (!$this->verifyTransaction($transaction->trx_ref)) {
            return false;
        }

        if (!$transaction->status) {
            $transaction->setStatus(true);
            $accountBalance = new AccountBalanceService(Auth::user());
            $accountBalance->updateAccountBalance($transaction->amount);
            return true;
        }

        return false;
    }

    private function verifyTransaction($transactionId): bool
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'bearer ' . $this->token(),
        ])->get(self::getUrl() . "api/v2/transactions/$transactionId");

        $response = $response->object();

        if (!isset($response->requestSuccessful) && !isset($response->responseBody->paymentStatus)) return false;

        if ($response->requestSuccessful && $response->responseBody->paymentStatus == 'PAID') {
            return true;
        }

        return false;
    }

    public static function createVirtualAccount($user)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . self::token(),
            ])->post(self::getUrl() . "api/v2/bank-transfer/reserved-accounts", [
                "accountReference"      =>  self::generateVirtualAccountReference(),
                "accountName"           =>  ucwords($user->username),
                "currencyCode"          =>  "NGN",
                "contractCode"          =>  static::monnifyDetails('contract_code'),
                "customerEmail"         =>  $user->email,
                "customerName"          =>  $user->name,
                "getAllAvailableBanks"  =>  true,
            ]);

            $response = $response->object();

            if ($response->requestSuccessful) {
                $data = [];
                foreach ($response->responseBody->accounts as $account) {
                    $data [] = [
                        "reference" => $response->responseBody->accountReference,
                        "bank_code" => $account->bankCode,
                        "bank_name" => $account->bankName,
                        "account_name" => $account->accountName,
                        "account_number" => $account->accountNumber,
                        "reservation_reference" => $response->responseBody->reservationReference,
                        "reserved_account_type" => $response->responseBody->reservedAccountType,
                        "restrict_payment_source" => $response->responseBody->restrictPaymentSource,
                        "collection_channel" => $response->responseBody->collectionChannel,
                        "status" => $response->responseBody->status,
                        "created_on" => $response->responseBody->createdOn,
                        "status" => $response->responseBody->status,
                        "created_at" => now(),
                        "updated_at" => now(),
                        "user_id" => $user->id
                    ];
                }

                VirtualAccount::insert($data);                
                return true;
            }
    
            return false;

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public static function verifyKyc($kyc)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . self::token(),
            ])->put(self::getUrl() . "api/v1/bank-transfer/reserved-accounts/" . self::getAccountReference() . "/kyc-info", [
                "bvn" => $kyc
            ]);

            $response = $response->object();
            
            if ($response->requestSuccessful) {
                self::updateAccountKyc($response->responseBody->bvn);
                return response()->json([
                    'status'    =>    true,
                    'error'     =>    NULL,
                    'message'   =>    "BVN linked to your account successfully.",
                ], 200)->getData();
            }


            if (!$response->requestSuccessful) {
                return response()->json([
                    'status'  => false,
                    'error'   => 'Invalid BVN.',
                    'message' => "Invalid BVN provided."
                ], 401)->getData();
            }

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

    }

    public static function computeRequestValidationHash(string $stringifiedData)
    {
        $clientSK = static::monnifyDetails('key');
        return hash_hmac('sha512', $stringifiedData, $clientSK);
    }

    public static function webhook(Request $request)
    {
        try {
            // Log::info('Monnify Webhook Payload: ', $request->all());
            // Verify the webhook signature
            $monnifySignature = $request->header('monnify-signature');

            $stringifiedData = json_encode($request->all());
            $payload = $request->input('eventData');

            $calculatedHash = self::computeRequestValidationHash($stringifiedData);

            if ($calculatedHash !== $monnifySignature) {
                return response()->json(['message' => 'Invalid signature'], 400);
            } 

            // Handle the payment notification
            $eventType = $request->eventType;
            $eventData = $request->eventData;

            if ($eventType === 'SUCCESSFUL_TRANSACTION') {

                $amountPaid = $eventData['amountPaid'];
                $customerEmail = $eventData['customer']['email'];
                $metaData = $eventData['metaData'];
                $transactionReference = $eventData['transactionReference'];
                $paymentReference = $eventData['paymentReference'];
                $paymentStatus = $eventData['paymentStatus'];

                // Find the user and update their balance
                $user = User::where('email', $customerEmail)->first();

                if ($user) {

                    $transaction = MonnifyTransaction::create([
                        'reference_id'  => $paymentReference,
                        'trx_ref'       => $transactionReference,
                        'user_id'       => $user->id,
                        'amount'        => $amountPaid,
                        'currency'      => config('app.currency', 'NGN'),
                        'redirect_url'  => config('app.url'),
                        'meta'          => $payload,
                        'status'        => $paymentStatus == 'PAID' ? true : false
                    ]);

                    $user->setAccountBalance($amountPaid);

                    return response()->json([
                        'status'   =>    true,
                        'error'    =>    NULL,
                        'message'  =>    "Transaction successful",
                        'response' =>    $transaction
                    ], 200)->getData();
                }

                return response()->json([
                    'status'   =>    false,
                    'error'    =>    "User not found",
                    'message'  =>    "Transaction not successful",
                    'response' =>    []
                ], 200)->getData();
            }

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'status'   =>    false,
                'error'    =>    "Server Error",
                'message'  =>    "",
                'response' =>    []
            ], 200)->getData();
        }
    }

    public static function getAccountReference()
    {
        return Auth::user()->virtualAccounts->first()?->reference;
    }

    public static function updateAccountKyc($bvn)
    {
        Auth::user()->virtualAccounts()->update(['bvn' => $bvn]);
    }
    
    private static function monnifyDetails($colunm)
    {
        return PaymentGateway::whereName('Monnify')->first()->$colunm ?? NULL;
    }

    public function generateUniqueId(): string
    {
        return 'monnify_' . Str::random(10) . Str::replace(' ', '', microtime()) . Str::random(4);
    }

    public static function generateMoneyTransferReference(): string
    {
        $referenceId = Str::random(25);
        // Filter out characters that are not alphanumeric, hyphens, or underscores
        $referenceId = preg_replace('/[^a-zA-Z0-9_-]/', '', $referenceId);
        return $referenceId;
    }

    public static function generateVirtualAccountReference(): string
    {
        $referenceId = Str::random(15);
        // Filter out characters that are not alphanumeric, hyphens, or underscores
        $referenceId = preg_replace('/[^a-zA-Z0-9_-]/', '', $referenceId);
        return $referenceId;
    }

    public static function getUrl()
    {
        if (app()->environment() == 'production') {
            return static::LIVE;
        }

        return static::TEST;
    }
}
