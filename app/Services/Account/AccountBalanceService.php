<?php

namespace App\Services\Account;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Payment\CryptoWallet;

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

    
    
    /**
     * Update the user's account balance by adding the specified amount.
     *
     * This method calls the `setAccountBalance` method on the user model,
     * which is responsible for updating the user's account balance with the
     * specified amount.
     *
     * @param float $amount The amount to be added to the user's account balance.
     * @return bool Returns true if the operation was successful.
     */
    public function updateAccountBalance($amount)
    {
        $this->user->setAccountBalance($amount);
        return true;
    }

    /**
     * Perform a transaction by removing the specified amount from the user's balance.
     *
     * This method calls the `setTransaction` method on the user model,
     * which handles deducting the specified amount from the user's account balance.
     *
     * @param float $amount The amount to be removed from the user's account balance.
     * @return bool Returns true if the transaction was processed successfully.
     */
    public function transaction($amount)
    {
        $this->user->setTransaction($amount);
        return true;
    }


    public function verifyAccountBalance($amount, $wallet = 'base_wallet') : bool
    {
        if ($wallet === 'base_wallet') {
            return $this->getAccountBalance() >= $amount;
        }

        if ($wallet === 'crypto_wallet') {
            $cryptoWallet = CryptoWallet::where('user_id', $this->id)->first();
            return $cryptoWallet && $cryptoWallet->balance >= $amount;
        }

        // fallback: treat unknown wallet types as base_wallet
        return $this->getAccountBalance() >= $amount;
    }


    public function initiateRefund($user, $amount, $transaction, $wallet = 'base_wallet') : bool
    {
        if ($wallet === 'base_wallet') {
            $user->account_balance += $amount;
            $user->save();
        } elseif ($wallet === 'crypto_wallet') {
            $cryptoWallet = CryptoWallet::where('user_id', $user->id)->first();
            if ($cryptoWallet) {
                $cryptoWallet->balance += $amount;
                $cryptoWallet->save();
            }
        }

        $transaction->balanceAfterRefund($amount);
        $transaction->refund();

        return true;
    }


    public function initiateDebit($user, $amount, $transaction) : bool
    {
        // $this->user->setAccountBalance($amount);
        $user->account_balance -= $amount;
        $user->save();
        $transaction->debit();
        return true;
    }

    public function initiatePending($amount, $transaction) : bool
    {
        // $this->user->setAccountBalance($amount);
        $transaction->pending();
        return true;
    }

    public function initiateSuccess($amount, $transaction) : bool
    {
        // $this->user->setAccountBalance($amount);
        $transaction->success();
        return true;
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