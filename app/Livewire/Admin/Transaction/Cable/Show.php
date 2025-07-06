<?php

namespace App\Livewire\Admin\Transaction\Cable;

use App\Models\Utility\CableTransaction;
use Livewire\Component;

class Show extends Component
{
    public $cable;

    public function mount($id)
    {
        $this->cable = CableTransaction::with(['user' => function($query) {
            $query->withTrashed();
        }, 'vendor'])->findOrFail($id);
        $this->authorize('view cable transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.cable.show');
    }
}
