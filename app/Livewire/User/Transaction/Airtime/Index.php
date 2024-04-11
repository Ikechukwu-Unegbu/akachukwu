<?php

namespace App\Livewire\User\Transaction\Airtime;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Utility\AirtimeTransaction;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 20;
    public $perPages = [20, 50];
    public $search;

    public function render()
    {
        return view('livewire.user.transaction.airtime.index', [
            'airtime_transactions' => AirtimeTransaction::with('network')
                                        ->search($this->search)
                                        ->whereUserId(auth()->id())
                                        ->latest()
                                        ->paginate($this->perPage)
        ])->layout('layouts.new-guest');
    }
}
