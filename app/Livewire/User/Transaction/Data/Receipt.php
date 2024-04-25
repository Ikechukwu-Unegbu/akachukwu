<?php

namespace App\Livewire\User\Transaction\Data;

use App\Models\Data\DataTransaction;
use Livewire\Component;

class Receipt extends Component
{
    public $data;

    public function mount(DataTransaction $data)
    {
        $this->data = $data;

        if ($this->data->user_id !== auth()->id()) return $this->redirectRoute('user.transaction.data');

    }

    public function render()
    {
        return view('livewire.user.transaction.data.receipt')->layout('layouts.receipt');
    }
}
