<?php

namespace App\Livewire\Admin\Transaction\Reseller;

use Livewire\Component;
use App\Models\ResellerDiscount;

class Edit extends Component
{
    public $discount;
    public $reseller;
    public $status;

    protected $rules = [
        'discount' => ['required', 'numeric'],
        'status'   => ['required', 'boolean']
    ];

    public function mount(ResellerDiscount $reseller)
    {
        $this->reseller = $reseller;
        $this->discount = $this->reseller->discount;
        $this->status = $this->reseller->status ? true : false;
    }

    public function update()
    {
        $this->validate();
        $this->reseller->update([
            'discount' => $this->discount,
            'status'   =>  $this->status
        ]);

        $this->dispatch('success-toastr', ['message' => "Reseller Discount Updated Successfully"]);
        session()->flash('success', "Reseller Discount Updated Successfully");
        $this->redirectRoute('admin.transaction.reseller');
    }
    
    public function render()
    {
        return view('livewire.admin.transaction.reseller.edit');
    }
}
