<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\Crypto\CryptoFundingWebhookService;
use App\Services\Payment\Crypto\QuidaxRequeryService;
use App\Services\Payment\Crypto\QuidaxTransferService;
use Illuminate\Support\Facades\DB;
use App\Services\Payment\Crypto\QuidaxSwapService;
use App\Services\Payment\Crypto\WalletService;
use App\Services\Payment\Crypto\QuidaxxService;


class QuidaxWebhookController extends Controller
{
    protected $requeryService;
    public $transferService;

    public function __construct(QuidaxRequeryService $requeryService, QuidaxTransferService $transferService)
    {
        $this->requeryService = $requeryService;
         $this->transferService = $transferService;
    }


    public function __invoke(Request $request)
    {
         



        Log::info('Incoming Quidax Webhook', $request->all());
   

        $event = $request->input('event');
        $data = $request->all();

        $eventId = $request->input('id') ?? ($data['data']['id'] ?? null);
       

        if($event == 'deposit.successful'){
            Log::info('Requerying deposite.successful event with id: ', [$eventId]);
            $requeryResult = $this->requeryService->reQueryDeposit($eventId, $request->data['wallet']['user']['id']);
       

            if (($requeryResult->status ?? null) == 'success' || config('app.env') != 'production') {
                //defining variables from the deposite query result.
                $amount = $requeryResult->response->data->amount;
                $currency = $requeryResult->response->data->currency;
                $userQuidaxId = $requeryResult->response->data->wallet->user->id;
                $localUser = \App\Models\User::where('quidax_id', $userQuidaxId)->first();

                //Log funding to CryptoTransactionsLog table
               $exists = \App\Models\Payment\CryptoTransactionsLog::where('txid', $requeryResult->response->data->txid)
                    ->where('transaction_id', $requeryResult->response->data->id)
                    ->exists();

                if ($exists && app()->environment('production')) {
                    abort(400, 'Duplicate crypto transaction log found.');
                }


                $cryptoTransactionLog = \App\Models\Payment\CryptoTransactionsLog::create([
                    'txid'             => $requeryResult->response->data->txid,
                    'transaction_id'   => $requeryResult->response->data->id,
                    'user_id'          => $localUser->id,
                    'amount_in_crypto' => $amount,
                    'amount'           => $requeryResult->response->data->wallet->converted_balance,
                    'fee'              => 0.00,
                    'currency'         => $currency,
                    'status'           => $requeryResult->response->data->status,
                    'meta'             => json_encode($requeryResult->response->data),
                ]);

                // Swap funds to NGN 
                $service = new QuidaxSwapService(new QuidaxxService());
              
                $result = $service->generateSwapQuotation(
                    $localUser->quidax_id,
                    $requeryResult->response->data->currency,
                    $requeryResult->response->data->amount,
                    'ngn'
                );
                Log::warning('Swap Quotation Result: ', (array)$result);
                if($result?->status == false){
                    Log::error('Error generating swap quotation: ', (array)$result);
                    return response()->json(['ok' => false], 200);
                }


                $confirm = null;
                if($result?->response->status == true || $result?->response->status == 'success'){
                    $swaid = $result->response->data->id;
                    $userQuidaxId = $result->response->data->user->id;
                    $confirm = $service->confirmQuidaxSwap($swaid, $userQuidaxId);
                    Log::warning('Swap Confirm Result: ', (array)$confirm->response);

                }else{
                    Log::error('Error generating swap quotation: ', (array)$result->response);
                }


                //transfer funds to master account
                $narration = "Transfer from user {$userQuidaxId} after deposit";
                $transactionNote = "Auto transfer from user {$userQuidaxId} after deposit";
                $parentReciever = config('services.quidax.master_account_id', env('QUIDAX_MASTER_ACCOUNT_ID'));
                
                sleep(10);

                $transferResult = $this->transferService->transferFunds(
                    $confirm->response->data->received_amount, 
                    'ngn',
                    $transactionNote,
                    $narration,
                    $parentReciever,
                    $userQuidaxId
                );
                // credit the user local wallet with NGN
                if ($transferResult?->status === true && $transferResult?->response?->status === 'success') {
                    DB::transaction(function () use ($localUser, $confirm) {
                        $cryptoWallet = \App\Models\Payment\CryptoWallet::where('user_id', $localUser->id)
                            ->lockForUpdate() // ⛔ locks the row until transaction ends
                            ->first();

                        $cryptoWallet->balance += $confirm->response->data->received_amount;
                        $cryptoWallet->save();
                    });
                }else{
                    Log::error('Error transferring funds to master account: ', (array)$transferResult->response);
                }

            }

          
        
        }
      

        return response()->json(['ok' => true], 200);
    }
}


