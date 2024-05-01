<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\AdminTopupNotification;
use App\Notifications\FundDeductionNotification;
use App\Services\Account\AccountBalanceService;
use Illuminate\Support\Facades\Session;

// use Illuminate\Notifications\Notifiable;

class UserCrdDbtController extends Controller
{
    public function index()
    {
        $username = request()->input('username');
        $user = User::where('username', $username)->first();

        return view('system-user.crd-dbt.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|integer',
            'action' => 'required|string',
            'reason' => 'required|string',
            'username' => 'required|string'
        ]);
        $user = User::where('username', $validatedData['username'])->first();
        if ($validatedData['action'] == 'credit') {
            
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
            $user->notify(new FundDeductionNotification($validatedData));

        }
        Session::flash('success', 'Action Successfull.');
        return redirect()->back();
    }

}
