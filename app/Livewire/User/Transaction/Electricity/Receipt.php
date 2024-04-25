<?php

namespace App\Livewire\User\Transaction\Electricity;

use App\Models\Utility\ElectricityTransaction;
use Livewire\Component;

class Receipt extends Component
{
    public $electricity;

    public function mount(ElectricityTransaction $electricity)
    {
        $this->electricity = $electricity;

        if ($this->electricity->user_id !== auth()->id()) return $this->redirectRoute('user.transaction.electricity');
    }

    public function render()
    {
        return view('livewire.user.transaction.electricity.receipt')->layout('layouts.receipt');
    }
}
