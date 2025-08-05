<?php

namespace App\Livewire\Admin\Transaction\ResultChecker;

use App\Models\Education\ResultCheckerTransaction;
use Livewire\Component;

class Show extends Component
{
    public $resultChecker;

    public function mount($id)
    {
        $this->resultChecker = ResultCheckerTransaction::with(['user' => function($query) {
            $query->withTrashed();
        }])->findOrFail($id);
        $this->authorize('view result-checker transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.result-checker.show');
    }
}
