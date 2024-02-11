<?php

namespace App\Livewire\Admin\Transaction\Airtime;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Utility\AirtimeTransaction;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public function mount()
    {
        $this->authorize('view airtime transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.airtime.index', [
            'airtime_transactions' =>  AirtimeTransaction::with(['user', 'network'])->search($this->search)->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
