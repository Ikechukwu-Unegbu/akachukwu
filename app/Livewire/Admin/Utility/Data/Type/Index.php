<?php

namespace App\Livewire\Admin\Utility\Data\Type;

use Livewire\Component;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Models\Data\DataType;

class Index extends Component
{
    public $vendor;
    public $network;

    public function mount(DataVendor $vendor, DataNetwork $network)
    {
        $this->authorize('view data utility');
        $this->vendor = $vendor;
        $this->network = $network;
    }
    
    public function render()
    {
        return view('livewire.admin.utility.data.type.index', [
            'dataTypes' => DataType::withCount([
                            'dataPlans' => fn ($query) => $query->where('vendor_id', $this->vendor->id)
                            ])->whereVendorId($this->vendor->id)
                            ->whereNetworkId($this->network->network_id)
                            ->get()
        ]);
    }
}
