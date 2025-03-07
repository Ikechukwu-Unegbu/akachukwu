<?php

namespace App\Livewire\Admin\Transaction\MoneyTransfer;

use App\Models\MoneyTransfer;
use Livewire\Component;

class Show extends Component
{

    public $moneyTransfer;

    public function mount(MoneyTransfer $moneyTransfer)
    {
        $this->moneyTransfer = $moneyTransfer;
        $this->authorize('view money transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.money-transfer.show');
    }
}
