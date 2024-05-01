<?php

namespace App\Livewire\Admin\CrdDbt;

use Livewire\Component;
use Illuminate\Http\Request;

class Create extends Component
{
    public $username;

    public function mount(Request $request)
    {
        $this->username = $request->query('username');
    }
    public function render()
    {
        return view('livewire.admin.crd-dbt.create');
    }
}
