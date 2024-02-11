<?php

namespace App\Livewire\Admin\Api\Vendor;

use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public function mount()
    {
        $this->authorize('view vendor api');   
    }

    public function render()
    {
        return view('livewire.admin.api.vendor.index', [
            'vendors' =>    Vendor::search($this->search)->paginate($this->perPage)
        ]);
    }
}
