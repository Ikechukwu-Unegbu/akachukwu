<?php

namespace App\Livewire\User\Transaction\Airtime;

use App\Models\Utility\AirtimeTransaction;
use Livewire\Component;

class Receipt extends Component
{
    public $airtime;

    public function mount(AirtimeTransaction $airtime)
    {
        $this->airtime = $airtime;

        if ($this->airtime->user_id !== auth()->id()) return $this->redirectRoute('user.transaction.airtime');

    }

    public function render()
    {
        return view('livewire.user.transaction.airtime.receipt')->layout('layouts.receipt');
    }
}
