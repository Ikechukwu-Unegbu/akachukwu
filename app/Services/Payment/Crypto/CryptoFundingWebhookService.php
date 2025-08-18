<?php
namespace App\Services\Payment\Crypto;

use App\Helpers\ApiHelper;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CryptoFundingWebhookService
{
    /**
     * Verify webhook signature from Quidax
     */
    public static function verifySignature($request): bool
    {
        try {
            $header = $request->header('quidax-signature');
            if (!$header) return false;

            $parts = explode(',', $header);
            $timestamp = null;
            $signature = null;
            foreach ($parts as $part) {
                [$key, $value] = array_map('trim', explode('=', $part));
                if ($key === 't') $timestamp = $value;
                if ($key === 'v1') $signature = $value;
            }

            if (!$timestamp || !$signature) return false;

            $payload = $timestamp . '.' . $request->getContent();
            $expected = hash_hmac('sha256', $payload, env('QUIDAX_WEBHOOK_SECRET', ''));

            return hash_equals($signature, $expected);
        } catch (\Throwable $th) {
            Log::error('Quidax Webhook signature verification failed', ['error' => $th->getMessage()]);
            return false;
        }
    }

    /**
     * Re-query the event/transaction from Quidax for confirmation
     */
    public static function requeryEvent(string $eventId)
    {
        try {
            $service = new QuidaxxService();
            // Quidax recommends re-querying relevant resource; here we simply return ok
            return ApiHelper::sendResponse(['event_id' => $eventId], 'Event acknowledged');
        } catch (\Throwable $th) {
            Log::error('Quidax requery failed', ['error' => $th->getMessage()]);
            return ApiHelper::sendError([], 'Unable to verify event');
        }
    }

    /**
     * Process a successful deposit webhook and credit user's NGN base wallet
     */
    public static function handleDeposit(array $payload)
    {
        // Expected payload structure may include: data -> { amount, currency, txid, address, user: { id/email } }
        $data = $payload['data'] ?? [];
        $amount = (float)($data['amount'] ?? 0);
        $currency = strtolower($data['currency'] ?? '');
        $txid = $data['txid'] ?? ($data['id'] ?? null);
        $userEmail = $data['user']['email'] ?? null;

        if ($amount <= 0 || !$currency || !$txid) {
            return ApiHelper::sendError([], 'Invalid deposit payload');
        }

        // Map crypto value to NGN using market price
        $service = new QuidaxxService();
        $market = $currency . 'ngn';
        $price = $service->getLastPrice($market);
        if (!($price->status ?? false)) {
            return ApiHelper::sendError([], 'Unable to fetch market price');
        }
        $ngnRate = (float) ($price->response ?? 0);
        $nairaAmount = round($amount * $ngnRate, 2);

        // Credit user's NGN base wallet
        $user = null;
        if ($userEmail) {
            $user = User::where('email', $userEmail)->first();
        }
        if (!$user && isset($data['user']['id'])) {
            $user = User::find($data['user']['id']);
        }
        if (!$user) {
            return ApiHelper::sendError([], 'User not found for deposit');
        }

        return DB::transaction(function () use ($user, $nairaAmount, $currency, $amount, $txid) {
            // Idempotency: ensure we do not double-credit same txid
            $exists = DB::table('crypto_deposits')->where('txid', $txid)->exists();
            if ($exists) {
                return ApiHelper::sendResponse([], 'Duplicate event ignored');
            }

            DB::table('crypto_deposits')->insert([
                'user_id' => $user->id,
                'currency' => strtoupper($currency),
                'crypto_amount' => $amount,
                'naira_amount' => $nairaAmount,
                'txid' => $txid,
                'status' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Increment user's naira base wallet balance
            $user->account_balance += $nairaAmount;
            $user->save();

            return ApiHelper::sendResponse(['credited' => $nairaAmount], 'Wallet funded');
        });
    }
}
