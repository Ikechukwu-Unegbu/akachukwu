<?php

namespace App\Services\Payment;

use Exception;
use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MoneyTransfer;
use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use Illuminate\Support\Collection;
use App\Interfaces\Payment\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Payment\MonnifyTransaction;
use App\Services\Account\AccountBalanceService;
use App\Services\Payment\VirtualAccountServiceFactory;

class MonnifyService implements Payment
{

    public static $accountBalance;

    private const LIVE = "https://api.monnify.com/";
    private const TEST = "https://sandbox.monnify.com/";

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
                'user_id'       => $user->id,
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
                'paymentMethods'        =>   ['CARD'],
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
            return response()->json([
                'status'   =>    false,
                'error'    =>    "Server Error",
                'message'  =>    "Opps! Unable to perform wallet funding. Please check your network connection.",
            ], 401)->getData();
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
            $transaction->failed();
            return false;
        }

        if (!$transaction->status) {
            // $transaction->setStatus(true);
            $accountBalance = new AccountBalanceService(Auth::user());
            $accountBalance->updateAccountBalance($transaction->amount);
            $transaction->success();
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

    public static function createVirtualAccount($user, $kyc, $kycType = 'bvn')
    {
     try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . self::token(),
            ])->post(self::getUrl() . "api/v2/bank-transfer/reserved-accounts", [
                "accountReference"      =>  self::generateVirtualAccountReference(),
                "accountName"           =>  Str::title($user->username),
                "currencyCode"          =>  "NGN",
                "contractCode"          =>  static::monnifyDetails('contract_code'),
                "customerEmail"         =>  $user->email,
                "customerName"          =>  $user->name,
                "getAllAvailableBanks"  =>  false,
                $kycType                =>  $kyc,
                "preferredBanks"        =>  ["035", "058", "50515"]
            ]);

            $response = $response->object();

            if ($response->requestSuccessful) {
                $data = [];
                foreach ($response->responseBody->accounts as $account) {
                    $data[] = [
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
                        "user_id" => $user->id,
                        "payment_id" => self::monnifyDetails('id')
                    ];
                }

                VirtualAccount::insert($data);
                return ApiHelper::sendResponse([], "Virtual Account Created Succeefully.");
            }


            $errorResponse = [
                'error'    =>    "Server Error",
                'message'  =>    "Opps! Unable to create static account. Please check your network connection.",
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error'    =>    "Server Error",
                'message'  =>    "Opps! Unable to create static account. Please check your network connection.",
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function verifyBvn($bvn, $code, $accountNumber)
    {
        try {return [
            "bvn"           => $bvn,
            "accountNumber" => $accountNumber,
            "bankCode"      => $code
        ];
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . self::token(),
            ])->post(self::getUrl() . "api/v1/vas/bvn-account-match", [
                "bvn"           => $bvn,
                "accountNumber" => $accountNumber,
                "bankCode"      => $code
            ]);

            $response = $response->object();
            // dd($response);
            if (isset($response->requestSuccessful) && $response->requestSuccessful === true) {
                self::updateAccountBvn($response->responseBody->bvn);

                if (!auth()->user()->virtualAccounts()->count()) {
                    // $activeGateway = PaymentGateway::where('va_status', true)->first();
                    // $virtualAccountFactory = VirtualAccountServiceFactory::make($activeGateway);
                    // $virtualAccountFactory::createVirtualAccount(auth()->user(), $response->responseBody->bvn, 'bvn');
                    self::createVirtualAccount(Auth::id(), $response->responseBody->bvn, 'bvn');
                    PayVesselService::createVirtualAccount(Auth::id(), $response->responseBody->bvn, 'bvn');
                }

                return ApiHelper::sendResponse([], "KYC updated & BVN linked to your account successfully.");
            }

            if (isset($response->requestSuccessful) && !$response->requestSuccessful) {
                $errorResponse = [
                    'error'    =>    "Invalid BVN.",
                    'message'  =>    "Service not available. Please try again later",
                ];
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            }
            $errorResponse = [
                'error'    =>    "API Endpoint error.",
                'message'  =>    "Service not available. Please try again later",
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error'    =>    "Server Error",
                'message'  =>    "Opps! Unable to update or create your static account. Please check your network connection.",
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function verifyNin($nin)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . self::token(),
            ])->post(self::getUrl() . "api/v1/vas/nin-details", [
                "nin" => $nin
            ]);

            $response = $response->object();
            // dd($response);
            if (isset($response->requestSuccessful) && $response->requestSuccessful === true) {
                self::updateAccountNin($response->responseBody->nin);

                if (!auth()->user()->virtualAccounts()->count()) {
                    // $activeGateway = PaymentGateway::where('va_status', true)->first();
                    // $virtualAccountFactory = VirtualAccountServiceFactory::make($activeGateway);
                    // $virtualAccountFactory::createVirtualAccount(auth()->user(), $response->responseBody->nin, 'nin');

                    self::createVirtualAccount(Auth::id(), $response->responseBody->nin, 'nin');
                    PayVesselService::createVirtualAccount(Auth::id(), $response->responseBody->nin, 'nin');
                }

                return ApiHelper::sendResponse([], "KYC updated & NIN linked to your account successfully.");
            }

            if (isset($response->requestSuccessful) && !$response->requestSuccessful) {
                $errorResponse = [
                    'error'    =>    "Invalid NIN.",
                    'message'  =>    "Service not available. Please try again later",
                ];
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            }
            $errorResponse = [
                'error'    =>    "API Endpoint error.",
                'message'  =>    "Service not available. Please try again later",
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error'    =>    "Server Error",
                'message'  =>    "Opps! Unable to update or create your static account. Please check your network connection.",
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    private static function computeSHA512TransactionHash($stringifiedData, $clientSecret)
    {
        return hash_hmac('sha512', $stringifiedData, $clientSecret);
    }

    public static function webhook(Request $request)
    {
        try {

            // Verify the webhook signature
            $webhook = [
                'ip'      => $request->ip(),
                'time'    => date('H:i:s'),
                'date'    => date('d-m-Y'),
                'payload' => $request->all(),
                'headers' => $request->headers->all()
            ];

            self::storePayload($webhook);

            $monnifySignature = $request->header('monnify-signature');
            $stringifiedData = $request->getContent();
            $payload = $request->input('eventData');
            $ipWhiteLists = [$request->header('cf-connecting-ip'), $request->header('x-forwarded-for')];
            $ipAddress = "35.242.133.146";

            $calculatedHash = self::computeSHA512TransactionHash($stringifiedData, static::monnifyDetails('key'));

            // Verify the hash
            if (!hash_equals($calculatedHash, $monnifySignature)) {
                return response()->json(['message' => 'Webhook payload verification failed.'], 400);
            }

            // if (!in_array($ipAddress, $ipWhiteLists)) {
            //     return response()->json(['message' => 'Webhook payload verification failed. IP Address not Found!.'], 400);
            // }

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

                    if (MonnifyTransaction::where('reference_id', $paymentReference)->where('status', true)->exists()) {
                        return response()->json(['message' => 'Payment Already Processed'], 200);
                    }

                    $transaction = MonnifyTransaction::updateOrCreate([
                        'reference_id'  => $paymentReference,
                        'trx_ref'       => $transactionReference,
                        'user_id'       => $user->id,
                    ], [
                        'amount'        => $amountPaid,
                        'currency'      => config('app.currency', 'NGN'),
                        'redirect_url'  => config('app.url'),
                        'meta'          => json_encode($payload)
                    ]);
                    
                    $transaction->success();

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
                'message'  =>    $th->getMessage(),
                'response' =>    []
            ], 401)->getData();
        }
    }

    public static function storePayload($payload)
    {
        $filename = 'monnify_payloads.json';
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

    public static function getAccountReference()
    {
        return Auth::user()->virtualAccounts->first()?->reference;
    }

    public static function updateAccountBvn($bvn)
    {
        Auth::user()->update(['bvn' => $bvn]);
    }

    public static function updateAccountNin($bvn)
    {
        Auth::user()->update(['nin' => $bvn]);
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
        $referenceId = preg_replace('/[^a-zA-Z0-9_-]/', '', $referenceId);
        return $referenceId;
    }

    public static function generateVirtualAccountReference(): string
    {
        $referenceId = Str::random(15);
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
