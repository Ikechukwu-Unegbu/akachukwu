<?php

namespace App\Livewire\Admin\Utility\Data;

use App\Models\Data\DataNetwork;
use App\Models\Data\DataVendor;
use Livewire\Component;

class Index extends Component
{

    public $vendor;
    protected $queryString = ['vendor'];
 
    public function render()
    {
        return view('livewire.admin.utility.data.index', [
            'vendors'   =>  DataVendor::get(),
            'networks'  =>  $this->vendor ? DataNetwork::withCount([
                'dataTypes' => fn ($query) => $query->where('vendor_id', $this->vendor),        
                'dataPlans' => fn ($query) => $query->where('vendor_id', $this->vendor),    
            ])->whereVendorId($this->vendor)->get() : []
        ]);
    }
}
