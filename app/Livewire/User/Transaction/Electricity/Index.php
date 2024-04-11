<?php

namespace App\Livewire\User\Transaction\Electricity;

use App\Models\Utility\ElectricityTransaction;
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
        return view('livewire.user.transaction.electricity.index', [
            'electricity_transactions' => ElectricityTransaction::search($this->search)->whereUserId(auth()->id())->latest()->paginate($this->perPage)
        ])->layout('layouts.new-guest');
    }
}
