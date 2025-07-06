<?php

namespace App\Livewire\Admin\Transaction\Electricity;

use App\Models\Utility\ElectricityTransaction;
use Livewire\Component;

class Show extends Component
{
    public $electricity;

    public function mount($id)
    {
        $this->electricity = ElectricityTransaction::with(['user' => function($query) {
            $query->withTrashed();
        }, 'vendor'])->findOrFail($id);
        $this->authorize('view electricity transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.electricity.show');
    }
}
