<?php
namespace App\Services\Payment\Crypto;

use App\Helpers\ApiHelper;
use App\Models\User;
use App\Models\Payment\QuidaxTransaction;
use App\Services\UserWatchService;
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

            $requestBody = $request->getContent();
            $payload = $timestamp . '.' . $requestBody;
            $expected = hash_hmac('sha256', $payload, env('QUIDAX_WEBHOOK_SECRET', ''));

            return hash_equals($signature, $expected);
        } catch (\Throwable $th) {
            Log::error('Quidax Webhook signature verification failed', ['error' => $th->getMessage()]);
            return false;
        }
    }

    /**
     * Store webhook payload for debugging
     */
    public static function storePayload($webhook)
    {
        try {
            $logData = [
                'ip' => $webhook['ip'],
                'time' => $webhook['time'],
                'date' => $webhook['date'],
                'payload' => $webhook['payload'],
                'headers' => $webhook['headers']
            ];

            Log::info('Quidax Webhook Payload', $logData);
        } catch (\Throwable $th) {
            Log::error('Failed to store Quidax webhook payload', ['error' => $th->getMessage()]);
        }
    }

    /**
     * Process a successful deposit webhook and credit user's NGN base wallet
     */
    public static function handleDeposit(array $payload)
    {
        try {
            $data = $payload['data'] ?? [];

            // Extract data from Quidax webhook payload
            $transactionId = $data['id'] ?? null;
            $currency = strtolower($data['currency'] ?? '');
            $amount = (float)($data['amount'] ?? 0);
            $txid = $data['txid'] ?? null;
            $status = $data['status'] ?? '';
            $userEmail = $data['user']['email'] ?? null;
            $userId = $data['user']['id'] ?? null;

            if (!$transactionId || $amount <= 0 || !$currency || !$txid || $status !== 'accepted') {
                return ApiHelper::sendError([], 'Invalid deposit payload or status not accepted');
            }

            // Find user by email or Quidax user ID
            $user = null;
            if ($userEmail) {
                $user = User::where('email', $userEmail)->first();
            }
            if (!$user && $userId) {
                $user = User::where('quidax_id', $userId)->first();
            }

            if (!$user) {
                return ApiHelper::sendError([], 'User not found for deposit');
            }

            // Check if transaction already processed
            $existingTransaction = QuidaxTransaction::where('reference_id', $transactionId)->first();
            if ($existingTransaction && $existingTransaction->status) {
                return ApiHelper::sendResponse([], 'Payment Already Processed');
            }

            // Get NGN conversion rate
            $service = new QuidaxxService();
            $market = $currency . 'ngn';
            $priceResponse = $service->getLastPrice($market);

            if (!($priceResponse->status ?? false)) {
                return ApiHelper::sendError([], 'Unable to fetch market price for conversion');
            }

            $ngnRate = (float) ($priceResponse->response ?? 0);
            $nairaAmount = round($amount * $ngnRate, 2);

            // Create or update transaction record
            $transaction = QuidaxTransaction::updateOrCreate([
                'reference_id' => $transactionId,
                'trx_ref' => $txid,
                'user_id' => $user->id,
            ], [
                'amount' => $amount,
                'naira_amount' => $nairaAmount,
                'currency' => strtoupper($currency),
                'meta' => json_encode($payload)
            ]);

            // Credit user's account using existing pattern
            $user->setAccountBalance($nairaAmount);
            $transaction->success();

            // Enforce post no debit if applicable
            UserWatchService::enforcePostNoDebit($user);

            return ApiHelper::sendResponse([
                'status' => true,
                'error' => null,
                'message' => "Crypto deposit successful",
                'response' => $transaction
            ], 'Transaction processed successfully');

        } catch (\Throwable $th) {
            Log::error('Quidax deposit processing failed', [
                'error' => $th->getMessage(),
                'payload' => $payload
            ]);

            return ApiHelper::sendError([], 'Server Error: ' . $th->getMessage());
        }
    }
}
