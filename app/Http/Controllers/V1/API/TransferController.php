<?php

namespace App\Http\Controllers\V1\API;

use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Helpers\GeneralHelpers;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\Account\AccountBalanceService;
use App\Notifications\MoneyTransferNotification;
use App\Services\Payment\Transfer\VastelMoneyTransfer;

class TransferController extends Controller
{
    public $helpers;
    public $vastelTransfer;

    public function __construct(GeneralHelpers $helpers)
    {
       $this->helpers = $helpers;
    }

    public function __invoke(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'recipient'=>'required|string', 
            'amount'=>'required', 
            'type'=>'required'
        ]);

        if ($validator->fails()) {
            return ApiHelper::sendError($validator->errors(), 'Failed validation');
        }

    
        if($request->type=='vastel'){
            $vastelTransfer = new VastelMoneyTransfer(Auth::user());
            $recipient = $vastelTransfer->getRecipient($request->recipient);
            $verifyRecipient =  $vastelTransfer->verifyRecipient($recipient);
            if (isset($verifyRecipient->status) && !$verifyRecipient->status) {
                return $verifyRecipient;
            }

            $user = User::find(Auth::id());

            $vastelTransferService =  $vastelTransfer->transfer($request->all());

            try {
                $user->notify(new MoneyTransferNotification($request->amount, 'intra', $vastelTransferService->status, $recipient->username, $user->account_balance));
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }

            return $vastelTransferService;

            // $accountBalanceService = new AccountBalanceService(Auth::user());
            // return $accountBalanceService->getAccountBalance();
            // if($accountBalanceService->verifyAccountBalance($request->amount) ==false){
            //     return ApiHelper::sendError(['Insufficient balance'], 'Insufficient balance');
            // }
            // if(!$this->vastelTransfer->getRecipient($request->recipient)){
            //     return ApiHelper::sendError(['No such user'], 'Unknown user');
            // }
            // $this->vastelTransfer->transfer($request->all(), $accountBalanceService);    
            // return  ApiHelper::sendResponse([], 'Transaction successful');
        }
          
    }
}
