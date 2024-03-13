<?php

namespace App\Services\Payment;

use Exception;
use App\Models\Paystack;
use App\Models\PaymentGateway;
use Illuminate\Support\Collection;
use App\Interfaces\Payment\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Exceptions\PaymentInitialisationError;
use App\Services\Account\AccountBalanceService;
use App\Models\Payment\Paystack as PaymentPaystack;

class PaystackService implements Payment
{
    public function isGatewayAvailable(): bool
    {
        return true;
    }

    public function createPaymentIntent($amount, $redirectURL, $user, array $meta = [])
    {

        $transaction = PaymentPaystack::create([
            'user_id'       =>  $user->id,
            'reference_id'  => $this->generateUniqueId(),
            'amount'        => $amount,
            'currency'      => config('app.currency', 'NGN'),
            'redirect_url'  => $redirectURL,
            'meta'          => json_encode($meta)
        ]);

        try {

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('services.paystack.secret-key', $this->secret_key()),
            ])->post('https://api.paystack.co/transaction/initialize', [
                'reference'     =>  $transaction->reference_id,
                'amount'        =>  $transaction->amount * 100,
                'currency'      =>  $transaction->currency,
                'email'         =>  $user->email,
                'callback_url'  =>  $transaction->redirect_url,
                'channels'      =>  ['card', 'bank', 'bank_transfer']
            ]);

            if (! $response->ok()) {
                throw new Exception('Invalid Response From Payment Gateway');
            }

            $response = $response->object();

            return collect([
                'paymentLink' => $response->data->authorization_url,
                'message' => $response->message,
                'status' => $response->status,
                'reference' =>  $response->data->reference
            ]);
            
        } catch (\Throwable $th) {
            
            Log::error($th->getMessage());
            // throw new PaymentInitialisationError('Could not initialize fluttterwave payment ');
        }
    }
 
    public function processPayment($request): bool
    {
        if (! $this->verifyTransaction($request->reference)) {
            return false;
        }

        $transaction = PaymentPaystack::where('reference_id', $request->reference)->first();

        if ($transaction == null || ! $transaction->exists()) {
            return false;
        }

        if ($transaction->status != true) {
            // auth()->user()->setAccountBalance($transaction->amount);
            
            $updateAccountBalance = new AccountBalanceService(Auth::user());
            $updateAccountBalance->updateAccountBalance($transaction->amount);

            $transaction->setStatus(true);
            return true;
        }

        return false;

    }

    private function verifyTransaction($transactionId): bool
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('services.flutterwave.secret-key', $this->secret_key()),
        ])->get("https://api.paystack.co/transaction/verify/$transactionId");
        
        $response = $response->object();

        if (! isset($response->status) || ! isset($response->data->status)) return false;
        
        if ($response->status || $response->data->status == 'success') {
            return true;
        }

        return false;
    }
  
    private function secret_key()
    {
        return PaymentGateway::whereName('Paystack')->first()?->key ?? NULL;
    }
   
    public function generateUniqueId(): string
    {
        return 'paystack_' . rand(1000, 99999999).str_replace(' ', '', microtime()).rand(1000, 99999999);

    }
}
