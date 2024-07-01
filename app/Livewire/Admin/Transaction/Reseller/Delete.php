<?php

namespace App\Livewire\Admin\Transaction\Reseller;

use Livewire\Component;
use App\Models\ResellerDiscount;

class Delete extends Component
{
    public $reseller;

    public function mount(ResellerDiscount $reseller)
    {
        $this->reseller = $reseller;
    }

    public function destroy()
    {
        $this->reseller->delete();
        $this->dispatch('success-toastr', ['message' => "Reseller Discount Deleted Successfully"]);
        session()->flash('success', "Reseller Discount Deleted Successfully");
        $this->redirectRoute('admin.transaction.reseller');
    }
    
    public function render()
    {
        return view('livewire.admin.transaction.reseller.delete');
    }
}
