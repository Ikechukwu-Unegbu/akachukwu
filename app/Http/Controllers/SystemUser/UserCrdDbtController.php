<?php

namespace App\Http\Controllers\SystemUser;

use App\Helpers\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Models\Payment\VastelTransaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\AdminTopupNotification;
use App\Notifications\FundDeductionNotification;
use App\Services\Account\AccountBalanceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

// use Illuminate\Notifications\Notifiable;

class UserCrdDbtController extends Controller
{
  

    public function store(Request $request)
    {
       return DB::transaction(function()use($request){
        $validatedData = $request->validate([
            'amount' => 'required|integer',
            'action' => 'required|string',
            'reason' => 'required|string',
            'username' => 'required|string'
        ]);
        $user = User::where('username', $validatedData['username'])->first();
        if ($validatedData['action'] == 'credit') {
            
            
            $vastelTransaction = new VastelTransaction();
            $vastelTransaction->admin_id = Auth::user()->id;
            $vastelTransaction->user_id = $user->id;
            $vastelTransaction->amount = $validatedData['amount'];
            $vastelTransaction->reference_id = GeneralHelpers::generateUniqueRef('vastel_transactions');
            $vastelTransaction->type = true;
            $vastelTransaction->currency = 'NGN';
            $vastelTransaction->status = true;
            $vastelTransaction->save();
            $vastelTransaction->success();

            $user->setAccountBalance($validatedData['amount']);
            $user->notify(new AdminTopupNotification($validatedData));
        }
        if ($validatedData['action'] == 'debit') {
            $balanceService = new AccountBalanceService($user);
            
            $balanceUpto = $balanceService->verifyAccountBalance($validatedData['amount']);
            if (!$balanceUpto) {
                return redirect()->back()->withErrors(['balanceUpto' => 'This user doesnt have upto this amount.']);
            }
            $balanceService->transaction($validatedData['amount']);
            $vastelTransaction = new VastelTransaction();
            $vastelTransaction->admin_id = Auth::user()->id;
            $vastelTransaction->user_id = $user->id;
            $vastelTransaction->amount = $validatedData['amount'];
            $vastelTransaction->reference_id = GeneralHelpers::generateUniqueRef('vastel_transactions');
            $vastelTransaction->type = false;
            $vastelTransaction->currency = 'NGN';
            $vastelTransaction->status = true;
            $vastelTransaction->save();
            $vastelTransaction->success();
            $user->notify(new FundDeductionNotification($validatedData));

        }
        Session::flash('success', 'Action Successfull.');
        return redirect()->back();
       });
    }

}
