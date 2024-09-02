<?php

namespace App\Livewire\Admin\Utility\Cable;

use App\Models\Vendor;
use Livewire\Component;

class Discount extends Component
{
    public $vendor;
    public $discounts = [];

    public function mount(Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->discounts = $this->vendor->cables->pluck('discount', 'id')->toArray();
        $this->authorize('view cable utility');
    }

    public function update()
    {
        $this->validate([
            'discounts' => ['array'],
            'discounts.*' => ['required', 'numeric', 'min:0']
        ], [
            'discounts.*.required' => 'The discount field is required.',
            'discounts.*.numeric' => 'The discount must be a number.',
            'discounts.*.min' => 'The discount must be at least :min.',
        ]);

        foreach ($this->discounts as $key => $value) {
            $this->vendor->cables->find($key)?->update(['discount' => $value]);
        }

        $this->dispatch('success-toastr', ['message' => 'Cable Discount Updated Successfully']);
        session()->flash('success', 'Cable Discount Updated Successfully');
        return redirect()->to(route('admin.utility.cable') . "?vendor={$this->vendor->id}");
    }
    
    public function render()
    {
        return view('livewire.admin.utility.cable.discount', [
            'cables' => $this->vendor->cables
        ]);
    }
}
