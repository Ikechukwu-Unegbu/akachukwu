<?php

namespace App\Livewire\Admin\Utility\Electricity;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $vendor;
    public $electricity;
    #[Rule('required|string')]
    public $disco_id;
    #[Rule('required|string')]
    public $disco_name;
    #[Rule('required|boolean')]
    public $status;
    #[Rule('required|numeric')]
    public $discount;
    #[Rule('nullable|image|mimes:png,jpg,jpeg|max:2048')]
    public $image;

    public function mount(Electricity $electricity, DataVendor $vendor) 
    {
        $this->vendor = $vendor;
        $this->electricity = $electricity;

        $this->disco_id = $this->electricity->disco_id;
        $this->disco_name = $this->electricity->disco_name;
        $this->discount = $this->electricity->discount;
        $this->status = $this->electricity->status ? true : false;
        $this->authorize('edit electricity utility');
    }

    public function update()
    {
        $this->validate();

        if ($this->disco_id !== $this->electricity->disco_id) {
            $checkIfDiscoIdExist = Electricity::whereVendorId($this->vendor->id)->whereDiscoId($this->disco_id)->where('id', '!=', $this->electricity->id)->count();
            if ($checkIfDiscoIdExist > 0) return $this->dispatch('error-toastr', ['message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"]);
        }

        if ($this->image) $this->electricity->deleteImage();
        $image = $this->image ? $this->image->storeAs('', Str::slug($this->disco_name) . '_' . time() . '.' . $this->image->getClientOriginalExtension(), 'electricity') : $this->electricity->image;

        $this->electricity->update([
            'disco_id'      =>  $this->disco_id,
            'disco_name'    =>  $this->disco_name,
            'status'        =>  $this->status,
            'discount'      =>  $this->discount,
            'image'         =>  $image
        ]);

        $this->dispatch('success-toastr', ['message' => 'Electricity Updated Successfully']);
        session()->flash('success', 'Electricity Updated Successfully');
        return redirect()->to(route('admin.utility.electricity') . "?vendor={$this->vendor->id}");
    }

    public function render()
    {
        return view('livewire.admin.utility.electricity.edit');
    }
}
