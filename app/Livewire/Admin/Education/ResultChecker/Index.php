<?php

namespace App\Livewire\Admin\Education\ResultChecker;

use App\Models\Vendor;
use Livewire\Component;
use App\Models\Education\ResultChecker;
use App\Services\Vendor\VendorServiceFactory;

class Index extends Component
{
    public $vendor;
    protected $queryString = ['vendor'];

    public function mount()
    {
        $get_vendor = ($this->vendor) ? Vendor::find($this->vendor) : Vendor::where('name', 'VTPASS')->first();
        $this->vendor = $get_vendor->id;
        $this->authorize('view e-pin');

        try {
            $vendorFactory = VendorServiceFactory::make($get_vendor);
            $vendorFactory::getEducationPins();
        } catch (\Throwable $th) {
            
        }
    }

    public function render()
    {
        return view('livewire.admin.education.result-checker.index', [
            'vendors'   =>  Vendor::get(),
            'result_checkers' => $this->vendor ? ResultChecker::where('vendor_id', $this->vendor)->get() : []
        ]);
    }
}
