<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\Crypto\CryptoFundingWebhookService;

class QuidaxWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            // Store webhook payload for debugging
            $webhook = [
                'ip' => $request->ip(),
                'time' => date('H:i:s'),
                'date' => date('d-m-Y'),
                'payload' => $request->all(),
                'headers' => $request->headers->all()
            ];

            CryptoFundingWebhookService::storePayload($webhook);

            // Verify signature (optional but recommended)
            if (!CryptoFundingWebhookService::verifySignature($request)) {
                Log::warning('Invalid Quidax webhook signature', [
                    'ip' => $request->ip(),
                    'signature' => $request->header('quidax-signature')
                ]);
                return response()->json(['message' => 'Webhook signature verification failed.'], 400);
            }

            $event = $request->input('event');
            $data = $request->all();

            Log::info('Quidax Webhook Event', [
                'event' => $event,
                'data' => $data
            ]);

            // Handle deposit events
            if ($event === 'deposit.successful') {
                $result = CryptoFundingWebhookService::handleDeposit($data);

                // Always return 200 to acknowledge receipt
                if ($result->status ?? false) {
                    return response()->json([
                        'status' => true,
                        'error' => null,
                        'message' => "Transaction successful",
                        'response' => $result->response ?? []
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'error' => $result->message ?? 'Transaction failed',
                        'message' => "Transaction not successful",
                        'response' => []
                    ], 200);
                }
            }

            // For other events, just acknowledge receipt
            return response()->json([
                'status' => true,
                'message' => 'Event received'
            ], 200);

        } catch (\Throwable $th) {
            Log::error('Quidax Webhook Error', [
                'error' => $th->getMessage(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'status' => false,
                'error' => "Server Error",
                'message' => $th->getMessage(),
                'response' => []
            ], 500);
        }
    }
}


