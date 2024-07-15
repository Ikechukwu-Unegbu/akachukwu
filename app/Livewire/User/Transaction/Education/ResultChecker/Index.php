<?php

namespace App\Livewire\User\Transaction\Education\ResultChecker;

use App\Models\Education\ResultCheckerTransaction;
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
        return view('livewire.user.transaction.education.result-checker.index', [
            'education_transactions' => ResultCheckerTransaction::withCount('result_checker_pins')->search($this->search)->whereUserId(auth()->id())->latest()->paginate($this->perPage)
        ])->layout('layouts.new-guest');
    }
}
