<?php

namespace App\Services\Payment;

use App\Exceptions\PaymentInitialisationError;
use App\Interfaces\Payment\Payment;
use App\Models\Payment\Flutterwave;
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

    public function createPaymentIntent($amount, $email, $redirectURL, array $meta = null): Collection
    {
        $transaction = Flutterwave::create([
            'reference_id' => $this->generateUniqueId(),
            'amount' => $amount,
            'currency' => config('app.currency'),
            'redirect_url' => $redirectURL,
            'meta' => $meta,
        ]);

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => config('services.flutterwave.secret-key'),
            ])->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $transaction->reference_id,
                'amount' => $transaction->amount->getAmount(),
                'currency' => $transaction->currency,
                'redirect_url' => $transaction->redirect_url,
                'meta' => $meta,
                'customizations' => [
                    'title' => config('app.name'),
                ],
                'customer' => [
                    'email' => $email,
                ],
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

        if (! $this->verifyTransaction($request->transaction_id, $request->amount, $request->currency)) {
            return false;
        }

        $transaction = Flutterwave::where('reference_id', $request->tx_ref)->first();

        if ($transaction == null || ! $transaction->exists()) {
            return false;
        }

        if ($transaction->status != 'processed') {
            $transaction->setStatus('processed');
        }

        return true;
    }

    private function verifyTransaction($transactionId, $amount, $currency): bool
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => config('services.flutterwave.secret-key'),
        ])->get("https://api.flutterwave.com/v3/transactions/$transactionId/verify");
        $response = $response->object();

        if ($response->status == 'success' || $response->data->status == 'successful') {
            return true;
        }

        return false;
    }

    public function generateUniqueId(): string
    {
        return Str::random(10).microtime().Str::random(4);

    }
}
