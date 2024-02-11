<?php

namespace App\Livewire\Admin\Utility\Cable;

use Livewire\Component;
use App\Models\Data\DataVendor;
use App\Models\Utility\Cable;

class Index extends Component
{
    public $vendor;
    protected $queryString = ['vendor'];

    public function mount()
    {
        $this->authorize('view cable utility');
    }

    public function render()
    {
        return view('livewire.admin.utility.cable.index', [
            'vendors'   =>  DataVendor::get(),
            'cables'    =>  $this->vendor ? Cable::withCount([
                'plans' => fn ($query) => $query->where('vendor_id', $this->vendor), 
            ])->whereVendorId($this->vendor)->get() : []
        ]);
    }
}
