<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Account\AccountBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Payment\Transfer\VastelMoneyTransfer;

class TransferController extends Controller
{
    private $user;
    private $accountBalanceService;

    public function __construct(public VastelMoneyTransfer $vastelTransfer)
    {
      
    }

    public function __invoke(Request $request)
    {
        // var_dump(User::find(Auth::user()->id));die;
        $request->validate([
            'recipient'=>'required|string', 
            'amount'=>'required'
        ]);
    
        if($request->type=='vastel'){
            $accountBalanceService = new AccountBalanceService(Auth::user());
            if($accountBalanceService->verifyAccountBalance($request->amount) ==false){
                return response()->json([
                    'status'=>'failed',
                    'message'=>'Insufficinet balance'
                ]);
            }
            $this->vastelTransfer->transfer($request->all(), $accountBalanceService);     
        }
        
    }
}
