<?php

namespace App\Livewire\Admin\Transaction\Electricity;

use App\Models\Utility\ElectricityTransaction;
use Livewire\Component;

class Index extends Component
{
    public $perPage = 50;

    public function render()
    {
        return view('livewire.admin.transaction.electricity.index', [
            'electricity_transactions' => ElectricityTransaction::with('user')->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
