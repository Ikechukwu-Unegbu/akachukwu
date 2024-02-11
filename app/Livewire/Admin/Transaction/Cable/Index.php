<?php

namespace App\Livewire\Admin\Transaction\Cable;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Utility\CableTransaction;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public function mount()
    {
        $this->authorize('view cable transaction');
    }
    
    public function render()
    {
        return view('livewire.admin.transaction.cable.index', [
            'cable_transactions' =>  CableTransaction::with(['user'])->search($this->search)->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
