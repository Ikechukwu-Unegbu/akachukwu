<?php

namespace App\Livewire\Admin\Hr\Reseller;

use App\Models\ResellerDiscount;
use Livewire\Component;

class Create extends Component
{
    public $discount_type;
    public $discount;

    public $discount_types = ['airtime', 'data', 'cable', 'electricity'];

    protected $rules = [
        'discount_type' => ['required', 'in:airtime,data,cable,electricity'],
        'discount' => ['required', 'numeric']
    ];

    public function store()
    {
        $this->validate();

        ResellerDiscount::create([
            'type'      =>  $this->discount_type,
            'discount'  =>  $this->discount,
        ]);

        $this->dispatch('success-toastr', ['message' => "Reseller Discount Added Successfully"]);
        session()->flash('success', "Reseller Discount Added Successfully");
        $this->redirectRoute('admin.hr.reseller');
    }

    public function render()
    {
        return view('livewire.admin.hr.reseller.create', [
            'reseller_discounts' =>    ResellerDiscount::get()->pluck('type', 'type')->toArray()
        ]);
    }
}
