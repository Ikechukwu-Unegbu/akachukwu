<?php

namespace App\Livewire\Admin\Transaction\Airtime;

use App\Models\Utility\AirtimeTransaction;
use Livewire\Component;

class Show extends Component
{
    public $airtime;

    public function mount($id)
    {
        $this->airtime = AirtimeTransaction::with(['user' => function($query) {
            $query->withTrashed();
        }, 'vendor'])->findOrFail($id);
        $this->authorize('view airtime transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.airtime.show');
    }
}
