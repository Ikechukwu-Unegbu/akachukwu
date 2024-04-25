<?php

namespace App\Livewire\User\Transaction\Data;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Data\DataTransaction;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 20;
    public $perPages = [20, 50];
    public $search;
    
    public function render()
    {
        return view('livewire.user.transaction.data.index', [
            'data_transactions' => DataTransaction::with('data_type')->search($this->search)->whereUserId(auth()->id())->latest()->paginate($this->perPage)
        ])->layout('layouts.new-guest');
    }
}
