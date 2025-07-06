<?php

namespace App\Livewire\Admin\Transaction\ResultChecker;

use App\Models\Education\ResultCheckerTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public function mount()
    {
        $this->authorize('view result-checker transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.result-checker.index', [
            'result_checker_transactions' => ResultCheckerTransaction::with(['user' => function ($query) {
                    $query->withTrashed();
                }])->search($this->search)->latest()->paginate($this->perPage)
        ]);
    }
}
