<?php

namespace App\Livewire\Admin\Utility\Electricity;

use Livewire\Component;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;

class Index extends Component
{
    public $vendor;
    protected $queryString = ['vendor'];

    public function mount()
    {
        $this->authorize('view electricity utility');
    }

    public function render()
    {
        return view('livewire.admin.utility.electricity.index', [
            'vendors'     =>  DataVendor::get(),
            'electricity' =>  $this->vendor ? Electricity::whereVendorId($this->vendor)->get() : []
        ]);
    }
}
