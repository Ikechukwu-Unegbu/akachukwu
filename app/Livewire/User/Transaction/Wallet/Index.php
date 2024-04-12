<?php

namespace App\Livewire\User\Transaction\Wallet;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 20;
    public $perPages = [20, 50];
    public $search;
    
    public function render()
    {
        $userId = auth()->id(); 

        $transactions = DB::table('flutterwave_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'flutter' as gateway_type"))
            ->where('user_id', $userId);

        $transactions->union(DB::table('paystack_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'paystack' as gateway_type"))
            ->where('user_id', $userId));

        $transactions->union(DB::table('monnify_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'monnify' as gateway_type"))
            ->where('user_id', $userId));

            // dd($transactions->get());
        
        return view('livewire.user.transaction.wallet.index', [
            'wallet_transactions' => $transactions->paginate($this->perPage)
        ])->layout('layouts.new-guest');
    }
}
