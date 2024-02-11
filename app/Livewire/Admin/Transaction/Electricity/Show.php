<?php

namespace App\Livewire\Admin\Transaction\Electricity;

use App\Models\Utility\ElectricityTransaction;
use Livewire\Component;

class Show extends Component
{
    public $electricity;

    public function mount(ElectricityTransaction $electricity)
    {
        $this->electricity = $electricity;
        $this->authorize('view electricity transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.electricity.show');
    }
}
