<?php

namespace App\Livewire\Admin\Utility\Airtime;

use Livewire\Component;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;

class Index extends Component
{
    public $vendor;
    protected $queryString = ['vendor'];

    public function mount()
    {
        $this->authorize('view airtime utility');
    }

    public function render()
    {
        return view('livewire.admin.utility.airtime.index', [
            'vendors'   =>  DataVendor::get(),
            'networks'  =>  $this->vendor ? DataNetwork::whereVendorId($this->vendor)->get() : []
        ]);
    }
}
