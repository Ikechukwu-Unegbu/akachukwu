<?php

namespace App\Livewire\Admin\Transaction\Electricity;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Utility\ElectricityTransaction;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public function render()
    {
        return view('livewire.admin.transaction.electricity.index', [
            'electricity_transactions' => ElectricityTransaction::with('user')->search($this->search)->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
