<?php

namespace App\Livewire\User\MoneyTransfer;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.user.money-transfer.index')->layout('layouts.new-guest');
    }
}
