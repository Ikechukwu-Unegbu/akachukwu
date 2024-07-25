<?php

namespace App\Livewire\Admin\Utility\Electricity;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;

class Create extends Component
{
    public $vendor;
    #[Rule('required|text')]
    public $disco_id;
    #[Rule('required|string')]
    public $disco_name;
    #[Rule('required|boolean')]
    public $status = true;

    public function mount(DataVendor $vendor) 
    {
        $this->vendor = $vendor;
        $this->authorize('create electricity utility');
    }

    public function store()
    {
        $this->validate();

        $checkIfDiscoIdExist = Electricity::whereVendorId($this->vendor->id)->whereDiscoId($this->disco_id)->count();
        if ($checkIfDiscoIdExist > 0) return $this->dispatch('error-toastr', ['message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"]);
        

        Electricity::create([
            'vendor_id'     =>  $this->vendor->id,
            'disco_id'      =>  $this->disco_id,
            'disco_name'    =>  $this->disco_name,
            'status'        =>  $this->status,
        ]);

        $this->dispatch('success-toastr', ['message' => 'Electricity Added Successfully']);
        session()->flash('success', 'Electricity Added Successfully');
        return redirect()->to(route('admin.utility.electricity') . "?vendor={$this->vendor->id}");
    }

    public function render()
    {
        return view('livewire.admin.utility.electricity.create');
    }
}
