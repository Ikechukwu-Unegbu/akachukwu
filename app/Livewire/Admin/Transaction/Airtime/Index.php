<?php

namespace App\Livewire\Admin\Transaction\Airtime;

use App\Models\Utility\AirtimeTransaction;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.transaction.airtime.index', [
            'airtime_transactions' =>  AirtimeTransaction::with(['user', 'network'])->latest('created_at')->paginate(50)
        ]);
    }
}
