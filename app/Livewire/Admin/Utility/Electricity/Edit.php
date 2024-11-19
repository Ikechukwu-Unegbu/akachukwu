<?php

namespace App\Livewire\Admin\Utility\Electricity;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;
use App\Models\Vendor;
use App\Services\Uploads\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    
    public function update(Request $request)
    {
        $electricity = Electricity::findOrFail($request->electricity);
        $vendor = Vendor::findOrFail($request->vendor);
        
        $request->validate([
            'disco_id' => 'required|string',
            'disco_name' => 'required|string',
            'status' => 'nullable',
            'discount' => 'required|numeric',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->disco_id !== $electricity->disco_id) {
            $checkIfDiscoIdExist = Electricity::whereVendorId($vendor->id)->whereDiscoId($request->disco_id)->where('id', '!=', $electricity->id)->count();
            if ($checkIfDiscoIdExist > 0) return $this->dispatch('error-toastr', ['message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"]);
        }

        if ($request->has('image') && $electricity->image) {
            $oldImagePath = str_replace(env('DO_CDN').'/', '', $electricity->image);
            Storage::disk('do')->delete($oldImagePath);
        }

        if ($request->has('image')) {
            $imageUrl = Storage::disk('do')->put('production/admin', $request->image, 'public');
            $electricity->image = env('DO_CDN').'/'.$imageUrl;
            $electricity->save();
        }

        $electricity->update([
            'disco_id'      =>  $request->disco_id,
            'disco_name'    =>  $request->disco_name, 
            'status'        =>  !$request->status ? false : true, 
            'discount'      =>  $request->discount,
            'image'         =>  $imageUrl ?? $electricity->image
        ]);

        $this->dispatch('success-toastr', ['message' => 'Electricity Updated Successfully']);
        session()->flash('success', 'Electricity Updated Successfully');
        return redirect()->to(route('admin.utility.electricity') . "?vendor={$vendor->id}");
    }

    public function render()
    {
        return view('livewire.admin.utility.electricity.edit');
    }
}
