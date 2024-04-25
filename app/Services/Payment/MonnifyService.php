<?php

namespace App\Services\Payment;

use Exception;
use Illuminate\Support\Str;
use App\Models\PaymentGateway;
use Illuminate\Support\Collection;
use App\Interfaces\Payment\Payment;
use App\Models\MoneyTransfer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Payment\MonnifyTransaction;
use App\Services\Account\AccountBalanceService;

class MonnifyService implements Payment
{

    public static $accountBalance;

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
            ])->post('https://sandbox.monnify.com/api/v1/auth/login');

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
            ])->post('https://sandbox.monnify.com/api/v1/merchant/transactions/init-transaction', [
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
            self::$accountBalance = new AccountBalanceService(Auth::user());

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
            ])->post('https://sandbox.monnify.com/api/v2/disbursements/single', [
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
            dd($e->getMessage());
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
            $updateAccountBalance = new AccountBalanceService(Auth::user());
            $updateAccountBalance->updateAccountBalance($transaction->amount);
            return true;
        }

        return false;
    }

    private function verifyTransaction($transactionId): bool
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'bearer ' . $this->token(),
        ])->get("https://sandbox.monnify.com/api/v2/transactions/$transactionId");

        $response = $response->object();

        if (!isset($response->requestSuccessful) && !isset($response->responseBody->paymentStatus)) return false;

        if ($response->requestSuccessful && $response->responseBody->paymentStatus == 'PAID') {
            return true;
        }

        return false;
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
}
