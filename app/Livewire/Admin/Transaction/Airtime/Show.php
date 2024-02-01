<?php

namespace App\Livewire\Admin\Transaction\Airtime;

use App\Models\Utility\AirtimeTransaction;
use Livewire\Component;

class Show extends Component
{
    public $airtime;

    public function mount(AirtimeTransaction $airtime)
    {
        $this->airtime = $airtime;
    }

    public function render()
    {
        return view('livewire.admin.transaction.airtime.show');
    }
}
