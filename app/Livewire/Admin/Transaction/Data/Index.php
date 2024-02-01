<?php

namespace App\Livewire\Admin\Transaction\Data;

use App\Models\Data\DataTransaction;
use Livewire\Component;

class Index extends Component
{
    public $perPage = 50;

    public function render()
    {
        return view('livewire.admin.transaction.data.index', [
            'data_transactions' =>  DataTransaction::with(['user', 'data_plan'])->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
