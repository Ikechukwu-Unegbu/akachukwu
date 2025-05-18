<?php

namespace App\Livewire\Admin\Api\Vendor;

use Carbon\Carbon;
use App\Models\Vendor;
use Livewire\Component;
use App\Models\Data\DataNetwork;
use Illuminate\Support\Facades\DB;
use App\Models\AirtimeVendorMapping;
use App\Models\VendorServiceMapping;

use Illuminate\Support\Facades\Auth;
use App\Services\Admin\Activity\ActivityLogService;

class Service extends Component
{
    
    public $vendor;
    public $service;
    public $airtimeService;
    public $network_name;

    public function mount()
    {
        $this->authorize('view vendor api');   
    }

    public function updateModal(VendorServiceMapping $service)
    {
        $this->service = $service;
        $this->vendor = $this->service->vendor->id;
        return true;
    }

    public function updateAirtimeNetworkModal($network)
    {
        $airtimeVendorMapping = AirtimeVendorMapping::where('network', $network)->first();
        $this->network_name = $network;
        if ($airtimeVendorMapping) {
            $this->airtimeService = $airtimeVendorMapping;
            $this->vendor = $this->airtimeService?->vendor?->id;
            return true;
        }
        return false;
    }

    public function updateService()
    {
        DB::transaction(function(){
            $this->service->update(['vendor_id' => $this->vendor]);
            ActivityLogService::log([
                'activity'=>"Update",
                'description'=>'Upding Service: '.$this->service->service_type.' Vendor To: '.Vendor::find($this->vendor)?->name,
                'type'=>'Vendors',
                'tags'=>['Update', 'VendorMapping']
            ]);
        });
      
        $this->dispatch('success-toastr', ['message' => 'Service Updated Successfully']);
        session()->flash('success', 'Service Updated Successfully');
        $this->redirect(url()->previous());
    }

    public function updateAirtimeService()
    {
        if (!DataNetwork::where('name', $this->network_name)->where('vendor_id', $this->vendor)->exists()) {
            $this->dispatch('error-toastr', [
                'message' => "Vendor could not be assigned to '{$this->network_name}' because the vendor network ID was not found."
            ]);
            return;
        }

        try {
            
            DB::beginTransaction();

            $pendingTransactionExists = DB::table('airtime_transactions')
                ->where('status', false)
                ->whereDate('created_at', '>=', Carbon::now()->subMinutes(1))
                ->sharedLock()
                ->exists();

            if ($pendingTransactionExists) {
                DB::rollBack();
                $this->dispatch('error-toastr', ['message' => 'Cannot update vendor mapping while transactions are in progress.']);
                session()->flash('error', 'Cannot update vendor mapping while transactions are in progress.');
                return $this->redirect(url()->previous());                
            }

            AirtimeVendorMapping::updateOrCreate(['network' => $this->network_name], ['vendor_id' => $this->vendor]);
            ActivityLogService::log([
                'activity'=>"Update",
                'description'=>'Changing airtime network ('.$this->network_name.') vendor to '.Vendor::find($this->vendor)?->name,
                'type'=>'Vendors',
                'tags'=>['Update', 'VendorMapping']
            ]);
            DB::commit();

            $this->dispatch('success-toastr', ['message' => 'Airtime Service Updated Successfully']);
            session()->flash('success', 'Airtime Service Updated Successfully');
            return $this->redirect(url()->previous());

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error-toastr', ['message' => 'Cannot update vendor mapping while transactions are in progress.']);
            session()->flash('error', 'Cannot update vendor mapping while transactions are in progress.');
            return $this->redirect(url()->previous());
        }

        
    }

    public function render()
    {
        return view('livewire.admin.api.vendor.service', [
            'vendor_services'  => VendorServiceMapping::with('vendor')->orderBy('service_type')->get(),
            'vendors'          => Vendor::get(),
            'networks'         => DataNetwork::with('airtimeMapping')->get()->unique('name')
        ]);
    }
}
