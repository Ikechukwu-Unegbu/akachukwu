<?php

namespace App\Livewire\User\Transaction\Cable;

use App\Models\Utility\CableTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 20;
    public $perPages = [20, 50];
    public $search;
    
    public function render()
    {
        return view('livewire.user.transaction.cable.index', [
            'cable_transactions' => CableTransaction::search($this->search)->whereUserId(auth()->id())->latest()->paginate($this->perPage)
        ])->layout('layouts.new-guest');
    }
}
