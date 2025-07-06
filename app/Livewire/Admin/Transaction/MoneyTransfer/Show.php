<?php

namespace App\Livewire\Admin\Transaction\MoneyTransfer;

use App\Models\MoneyTransfer;
use Livewire\Component;

class Show extends Component
{

    public $moneyTransfer;

    public function mount($id)
    {
        $this->moneyTransfer = MoneyTransfer::with(['user' => function($query) {
            $query->withTrashed();
        }])->findOrFail($id);
        // $this->authorize('view money transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.money-transfer.show');
    }
}
