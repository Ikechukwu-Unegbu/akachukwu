<?php

namespace App\Livewire\Admin\Transaction\Data;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Data\DataTransaction;

class Show extends Component
{
    public $data;

    public function mount(DataTransaction $data)
    {
        $this->data = $data;
    }

    public function render()
    {
        return view('livewire.admin.transaction.data.show');
    }
}
