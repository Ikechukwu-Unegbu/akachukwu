<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\Crypto\CryptoFundingWebhookService;
use App\Services\Payment\Crypto\QuidaxRequeryService;

class QuidaxWebhookController extends Controller
{
    protected $requeryService;

    public function __construct(RequeryService $requeryService)
    {
        $this->requeryService = $requeryService;
    }


    public function __invoke(Request $request)
    {
         



        Log::info('Incoming Quidax Webhook', $request->all());
        Log::info('Incoming Quidax Webhook Headers', $request->headers->all());



        $event = $request->input('event');
        $data = $request->all();

        // Requery can be triggered with event id if provided
        $eventId = $request->input('id') ?? ($data['data']['id'] ?? null);
      

        Log::info('Quidax Data Payload:', $data);

        if($event == 'deposit.successful'){
            Log::info('Requerying deposite.successful event with id: '[$eventId]);
            $this->requeryService->reQueryDeposit($eventId);
        
        }
        if($event == 'deposit.deposite.successful'){
            Log::info('Requerying deposite.successful event with id: '[$eventId]);
            $this->requeryService->reQueryDeposit($eventId);
        
        }
        // Handle deposit events
        // if (in_array($event, ['deposit.successful', 'wallet.deposit.successful', 'transaction.deposit.successful'])) {
        //     $result = CryptoFundingWebhookService::handleDeposit($data);
        //     // Always return 200 to acknowledge receipt
        //     return response()->json($result, 200);
        // }

        return response()->json(['ok' => true], 200);
    }
}


