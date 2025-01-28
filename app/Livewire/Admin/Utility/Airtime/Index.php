<?php

namespace App\Livewire\Admin\Utility\Airtime;

use Livewire\Component;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Models\SiteSetting;

class Index extends Component
{
    public $vendor;
    protected $queryString = ['vendor'];
    public $airtime_sales;

    public function mount()
    {
        $this->authorize('view airtime utility');
        $this->airtime_sales = SiteSetting::find(1)->airtime_sales;
    }

    public function updateAirtimeSale()
    {
        $siteSettings = SiteSetting::find(1);
        $siteSettings->updateAirtimeSale();
        $this->dispatch('success-toastr', ['message' => 'Airtime Sales Updated Successfully']);
        session()->flash('success', 'Airtime Sales Updated Successfully');
        return redirect()->to(url()->previous());
    }

    public function updateNetworkStatus(DataNetwork $dataNetwork)
    {
        $dataNetwork->update(['airtime_status' => !$dataNetwork->airtime_status]);
        $this->dispatch('success-toastr', ['message' => 'Network status Updated Successfully']);
        session()->flash('success', 'Network status Updated Successfully');
        return redirect()->to(url()->previous());
    }

    public function render()
    {
        return view('livewire.admin.utility.airtime.index', [
            'vendors'   =>  DataVendor::get(),
            'networks'  =>  $this->vendor ? DataNetwork::whereVendorId($this->vendor)->get() : []
        ]);
    }
}
