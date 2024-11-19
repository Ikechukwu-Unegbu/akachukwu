<?php

namespace App\Livewire\Admin\Utility\Electricity;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;
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
    #[Rule('nullable|image|mimes:png,jpg,jpeg|max:5048')]
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
        $this->validate();
        // dd($request);
        // dd(config('services.digitalocean.endpoint'));

        if ($this->disco_id !== $this->electricity->disco_id) {
            $checkIfDiscoIdExist = Electricity::whereVendorId($this->vendor->id)->whereDiscoId($this->disco_id)->where('id', '!=', $this->electricity->id)->count();
            if ($checkIfDiscoIdExist > 0) return $this->dispatch('error-toastr', ['message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"]);
        }

        if($this->image){
            if ($this->electricity->image) {
                $oldImagePath = str_replace(env('DO_CDN').'/', '', $this->electricity->image);
                Storage::disk('do')->delete($oldImagePath);
            }
            $imageUrl = Storage::disk('do')->put('production/admin', $this->image, 'public');
            $this->electricity->image = env('DO_CDN').'/'.$imageUrl;
            $this->electricity->save();
        }

        $this->electricity->update([
            'disco_id'      =>  $this->disco_id,
            'disco_name'    =>  $this->disco_name, 
            'status'        =>  $this->status, 
            'discount'      =>  $this->discount,
            'image'         =>  $imageUrl
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
