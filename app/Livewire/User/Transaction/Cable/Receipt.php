<?php

namespace App\Livewire\User\Transaction\Cable;

use App\Models\Utility\CableTransaction;
use Livewire\Component;

class Receipt extends Component
{
    public $cable;

    public function mount(CableTransaction $cable)
    {
        $this->cable = $cable;

        if ($this->cable->user_id !== auth()->id()) return $this->redirectRoute('user.transaction.cable');
    }

    public function render()
    {
        return view('livewire.user.transaction.cable.receipt')->layout('layouts.receipt');
    }
}
