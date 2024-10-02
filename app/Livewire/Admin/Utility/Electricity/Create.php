<?php

namespace App\Livewire\Admin\Utility\Electricity;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;

class Create extends Component
{
    use WithFileUploads;
    
    public $vendor;
    #[Rule('required|string')]
    public $disco_id;
    #[Rule('required|string')]
    public $disco_name;
    #[Rule('required|boolean')]
    public $status = true;
    #[Rule('required|numeric')]
    public $discount = 0;
    #[Rule('nullable|image|mimes:png,jpg,jpeg|max:2048')]
    public $image;

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
        
        $image = $this->image ? $this->image->storeAs('', Str::slug($this->disco_name) . '.' . $this->image->getClientOriginalExtension(), 'electricity') : null;

        Electricity::create([
            'vendor_id'     =>  $this->vendor->id,
            'disco_id'      =>  $this->disco_id,
            'disco_name'    =>  $this->disco_name,
            'status'        =>  $this->status,
            'discount'      =>  $this->discount,
            'image'         =>  $image
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
