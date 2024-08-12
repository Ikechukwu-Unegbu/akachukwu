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
        $transactions = auth()->user()->walletHistories();
        
        return view('livewire.user.transaction.wallet.index', [
            'wallet_transactions' => $transactions->paginate($this->perPage)
        ])->layout('layouts.new-guest');
    }
}
