<?php

namespace App\Livewire\Admin\Transaction\Data;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Data\DataTransaction;

class Show extends Component
{
    public $data;

    public function mount($id)
    {
        $this->data = DataTransaction::with(['user' => function($query) {
            $query->withTrashed();
        }, 'vendor'])->findOrFail($id);
        $this->authorize('view data transaction');
    }

    public function render()
    {
        return view('livewire.admin.transaction.data.show');
    }
}
