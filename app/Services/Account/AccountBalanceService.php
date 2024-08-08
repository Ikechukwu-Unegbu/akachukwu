<?php

namespace App\Services\Account;

use App\Models\User;
use Illuminate\Support\Facades\DB;

// use Illuminate\Foundation\Auth\User;
class AccountBalanceService 
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAccountBalance()
    {
        return $this->user->account_balance;
    }

    public function updateAccountBalance($amount)
    {
        $this->user->setAccountBalance($amount);
        return true;
    }

    public function transaction($amount)
    {
        $this->user->setTransaction($amount);
        return true;
    }

    public function verifyAccountBalance($amount) : bool
    {
        if ($this->getAccountBalance() >= $amount) return true;

        return false;
    }

    public function walletHistories()
    {
        $transactions = DB::table('flutterwave_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'flutter' as gateway_type"))
            ->where('user_id', $this->user->id)
            ->latest();

        $transactions->union(DB::table('paystack_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'paystack' as gateway_type"))
            ->where('user_id', $this->user->id))
            ->latest();

        $transactions->union(DB::table('monnify_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'monnify' as gateway_type"))
            ->where('user_id', $this->user->id))
            ->latest();

        $transactions->union(DB::table('pay_vessel_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'pay vessel' as gateway_type"))
            ->where('user_id', $this->user->id))
            ->latest();
        
        return $transactions;
    }

}