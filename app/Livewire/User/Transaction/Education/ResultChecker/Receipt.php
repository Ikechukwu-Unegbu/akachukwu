<?php

namespace App\Livewire\User\Transaction\Education\ResultChecker;

use App\Models\Education\ResultCheckerTransaction;
use Livewire\Component;

class Receipt extends Component
{
    public $checker;

    public function mount(ResultCheckerTransaction $checker)
    {
        $this->checker = $checker;

        if ($this->checker->user_id !== auth()->id()) return $this->redirectRoute('user.transaction.education');

    }

    public function render()
    {
        return view('livewire.user.transaction.education.result-checker.receipt')->layout('layouts.receipt');
    }
}
