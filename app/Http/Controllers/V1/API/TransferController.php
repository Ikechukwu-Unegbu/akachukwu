<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Account\AccountBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    private $user;
    private $accountBalanceService;

    public function __construct()
    {
        $this->user = User::find(Auth::user()->id);
        $this->accountBalanceService = new AccountBalanceService($this->user);
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'recipient'=>'required|string', 
            'amount'=>'required|string'
        ]);
    
        if($request->type=='vastel'){
            if($this->accountBalanceService->verifyAccountBalance($request->amount) ==false){
                return response()->json([
                    'status'=>'failed',
                    'message'=>'Insufficinet balance'
                ]);
                
            }
        }
        
    }
}
