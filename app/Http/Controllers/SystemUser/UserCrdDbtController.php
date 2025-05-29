<?php

namespace App\Http\Controllers\SystemUser;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\GeneralHelpers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Payment\VastelTransaction;
use App\Notifications\AdminTopupNotification;
use App\Services\Account\AccountBalanceService;
use App\Notifications\AdminDebitUserNotification;

// use Illuminate\Notifications\Notifiable;

class UserCrdDbtController extends Controller
{


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'action' => 'required|string',
            'reason' => 'required|string',
            'username' => 'required|string',
            'record'  =>    'required|boolean'
        ]);

       return DB::transaction(function() use($request, $validatedData) {

        $user = User::where('username', $validatedData['username'])->firstOrFail();

        if ($validatedData['action'] == 'credit') {
            $vastelTransaction = new VastelTransaction();
            $vastelTransaction->admin_id = Auth::user()->id;
            $vastelTransaction->user_id = $user->id;
            $vastelTransaction->amount = $validatedData['amount'];
            $vastelTransaction->reference_id = GeneralHelpers::generateUniqueRef('vastel_transactions');
            $vastelTransaction->type = true;
            $vastelTransaction->currency = 'NGN';
            $vastelTransaction->status = true;
            $vastelTransaction->record = $validatedData['record'];
            $vastelTransaction->balance_before =  $user->account_balance;
            $vastelTransaction->save();
            $vastelTransaction->success();
            $user->setAccountBalance($validatedData['amount']);
            $vastelTransaction->update(['balance_after' => $user->account_balance]);
            // $user->notify(new AdminTopupNotification($validatedData));
        }


        if ($validatedData['action'] == 'debit') {
            $balanceService = new AccountBalanceService($user);
            $balanceUpto = $balanceService->verifyAccountBalance($validatedData['amount']);
            if (!$balanceUpto) {
                return redirect()->back()->withErrors(['balanceUpto' => "This user doesn't have upto this amount."]);
            }

            $vastelTransaction = new VastelTransaction();
            $vastelTransaction->admin_id = Auth::user()->id;
            $vastelTransaction->user_id = $user->id;
            $vastelTransaction->amount = $validatedData['amount'];
            $vastelTransaction->reference_id = GeneralHelpers::generateUniqueRef('vastel_transactions');
            $vastelTransaction->type = false;
            $vastelTransaction->currency = 'NGN';
            $vastelTransaction->status = true;
            $vastelTransaction->record = $validatedData['record'];
            $vastelTransaction->balance_before =  $user->account_balance;
            $vastelTransaction->save();
            $vastelTransaction->success();
            $user->setTransaction($validatedData['amount']);
            // $user->notify(new AdminDebitUserNotification($validatedData));

            $vastelTransaction->update(['balance_after' => $user->account_balance]);

        }
            Session::flash('success', 'Action Successful.');
            return redirect()->back();
       });
    }

}
