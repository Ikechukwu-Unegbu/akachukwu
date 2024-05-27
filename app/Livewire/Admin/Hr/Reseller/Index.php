<?php

namespace App\Livewire\Admin\Hr\Reseller;

use App\Models\ResellerDiscount;
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
        $this->authorize('view users');
    }
    
    public function render()
    {
        return view('livewire.admin.hr.reseller.index', [
            'reseller_discounts' =>    ResellerDiscount::search($this->search)->latest()->paginate($this->perPage)
        ]);
    }
}
