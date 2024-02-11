<?php

namespace App\Livewire\Admin\Transaction\Data;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Data\DataTransaction;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public function mount()
    {
        $this->authorize('view data transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.data.index', [
            'data_transactions' =>  DataTransaction::with(['user', 'data_plan'])->search($this->search)->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
