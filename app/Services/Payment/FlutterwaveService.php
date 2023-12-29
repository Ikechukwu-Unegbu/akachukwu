<?php

namespace App\Services\Payment;

use App\Exceptions\PaymentInitialisationError;
use App\Interfaces\Payment\Payment;
use App\Models\Payment\Flutterwave;
use App\Models\PaymentGateway;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FlutterwaveService implements Payment
{
    public function isGatewayAvailable(): bool
    {
        return true;
    }

    public function createPaymentIntent($amount, $redirectURL, $user, array $meta = null)
    {
        $transaction = Flutterwave::create([
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
                'Authorization' => config('services.flutterwave.secret-key', $this->secret_key()),
            ])->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $transaction->reference_id,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'redirect_url' => $transaction->redirect_url,
                'meta' => $meta,
                'customizations' => [
                    'title' => config('app.name'),
                ],
                'customer' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);

            if (! $response->ok()) {
                throw new Exception('Invalid Response From Payment Gateway');
            }

            $response = $response->object();

            return collect([
                'paymentLink' => $response->data->link,
                'message' => $response->message,
                'status' => $response->status,
            ]);
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            // throw new PaymentInitialisationError('Could not initialize fluttterwave payment ');
        }
    }

    private function secret_key()
    {
        return PaymentGateway::whereName('Flutterwave')->first()?->key ?? NULL;
    }



    public function refundPayment($transactionId): bool
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => config('services.flutterwave.secret-key'),
            ])->post('https://api.flutterwave.com/v3/transactions/75923/refund', [
                'comments' => 'Reversing Double Charge',
            ]);
        } catch (\Throwable $th) {
            return false;
        }

        if ($response != 'success') {
            return false;
        }

        $transaction = Flutterwave::find($transactionId);

        if ($transaction != null && $transaction->exists()) {
            $transaction->setStatus('paid');
        }

        return true;
    }

    public function processPayment($request): bool
    {
        if ($request->status != 'successful' && $request->status != 'success') {
            return false;
        }

        if (! $this->verifyTransaction($request->transaction_id)) {
            return false;
        }

        $transaction = Flutterwave::where('reference_id', $request->tx_ref)->first();

        if ($transaction == null || ! $transaction->exists()) {
            return false;
        }

        if ($transaction->status != true) {
            $transaction->setStatus(true);
            auth()->user()->setAccountBalance($transaction->amount);
            $transaction->setTransactionId($request->transaction_id);
        }

        return true;
    }

    private function verifyTransaction($transactionId): bool
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => config('services.flutterwave.secret-key', $this->secret_key()),
        ])->get("https://api.flutterwave.com/v3/transactions/$transactionId/verify");

        $response = $response->object();

        if (! isset($response->status) || ! isset($response->data->status)) return false;

        if ($response->status == 'success' || $response->data->status == 'successful') {            
            return true;
        }

        return false;
    }

    public function generateUniqueId(): string
    {
        return Str::random(10).Str::replace(' ', '', microtime()).Str::random(4);
    }
}
