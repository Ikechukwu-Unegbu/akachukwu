<?php

namespace App\Livewire\Admin\Transaction\Cable;

use App\Models\Utility\CableTransaction;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.transaction.cable.index', [
            'cable_transactions' =>  CableTransaction::with(['user'])->latest('created_at')->paginate(50)
        ]);
    }
}
