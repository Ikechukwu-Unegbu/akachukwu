<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Helpers\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Account\AccountBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Payment\Transfer\VastelMoneyTransfer;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    private $user;
    private $accountBalanceService;

    public function __construct(public VastelMoneyTransfer $vastelTransfer, public GeneralHelpers $helpers)
    {
      
    }

    public function __invoke(Request $request)
    {
        // var_dump(User::find(Auth::user()->id));die;
        $validator = Validator::make($request->all(), [
            'recipient'=>'required|string', 
            'amount'=>'required', 
            'type'=>'required'
        ]);

        if ($validator->fails()) {
            return ApiHelper::sendError($validator->errors(), 'Failed validation');
        }

    
        if($request->type=='vastel'){
            $accountBalanceService = new AccountBalanceService(Auth::user());
            if($accountBalanceService->verifyAccountBalance($request->amount) ==false){
                return ApiHelper::sendError(['Insufficient balance'], 'Insufficient balance');
            }
            if(!$this->vastelTransfer->getRecipient($request->recipient)){
                return ApiHelper::sendError(['No such user'], 'Unknown user');
            }
            $this->vastelTransfer->transfer($request->all(), $accountBalanceService);    
            return  ApiHelper::sendResponse([], 'Transaction successful');
        }
          
    }
}
