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
            // Log request content to a dedicated file
            // $logFile = storage_path('logs/quidax_webhook.log');
      
            // file_put_contents(
            //     $logFile,
            //     "[" . now() . "] Incoming Webhook: " . print_r($request->all(), true) . PHP_EOL,
            //     FILE_APPEND
            // );


            Log::info('Incoming Quidax Webhook', $request->all());


        // Optional: verify signature
        if (!CryptoFundingWebhookService::verifySignature($request)) {
            Log::warning('Invalid Quidax webhook signature');
            return response()->json(['ok' => false], 401);
        }


        Log::info('Quidax signature is working');

        
        $event = $request->input('event');
        $data = $request->all();

        // Requery can be triggered with event id if provided
        $eventId = $request->input('id') ?? ($data['data']['id'] ?? null);
        if ($eventId) {
            CryptoFundingWebhookService::requeryEvent($eventId);
        }

        // Handle deposit events
        if (in_array($event, ['deposit.successful', 'wallet.deposit.successful', 'transaction.deposit.successful'])) {
            $result = CryptoFundingWebhookService::handleDeposit($data);
            // Always return 200 to acknowledge receipt
            return response()->json($result, 200);
        }

        return response()->json(['ok' => true], 200);
    }
}


