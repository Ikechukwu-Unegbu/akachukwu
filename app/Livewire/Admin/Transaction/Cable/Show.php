<?php

namespace App\Livewire\Admin\Transaction\Cable;

use App\Models\Utility\CableTransaction;
use Livewire\Component;

class Show extends Component
{
    public $cable;

    public function mount(CableTransaction $cable)
    {
        $this->cable = $cable;
    }

    public function render()
    {
        return view('livewire.admin.transaction.cable.show');
    }
}
