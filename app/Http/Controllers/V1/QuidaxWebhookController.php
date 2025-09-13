<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\Crypto\CryptoFundingWebhookService;
use App\Services\Payment\Crypto\QuidaxRequeryService;
use App\Services\Payment\Crypto\QuidaxTransferService;

class QuidaxWebhookController extends Controller
{
    protected $requeryService;
    public $transferService;

    public function __construct(RequeryService $requeryService, QuidaxTransferService $transferService)
    {
        $this->requeryService = $requeryService;
         $this->transferService = $transferService;
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
            $requeryResult = $this->requeryService->reQueryDeposit($eventId);
            if($requeryResult->status == 'success'){
                $amount = $requeryResult->data->amount;
                $currency = $requeryResult->data->currency;
                $userQuidaxId = $requeryResult->data->wallet->user->id;

                //transfer funds to master account
                $narration = "Transfer from user {$userQuidaxId} after deposit";
                $transactionNote = "Auto transfer from user {$userQuidaxId} after deposit";
                $targetQuidaxId = config('services.quidax.master_account_id', env('QUIDAX_MASTER_ACCOUNT_ID'));
                $transferResult = $this->transferService->transferFunds(
                    $amount, 
                    $currency,
                    $transactionNote,
                    $narration,
                    $userQuidaxId,
                    $targetQuidaxId,);

            }

          
        
        }
        if($event == 'deposit.deposite.successful'){
            Log::info('Requerying deposite.successful event with id: '[$eventId]);
            $requeryResult = $this->requeryService->reQueryDeposit($eventId);

        
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


