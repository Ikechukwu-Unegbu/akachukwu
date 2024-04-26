<?php

namespace App\Livewire\Pages\Utility\Cable;

use Livewire\Component;
use App\Models\Utility\Cable;
use App\Models\Data\DataVendor;
use App\Models\Utility\CablePlan;
use App\Services\Cable\CableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Utility\CableTransaction;
use App\Services\Account\AccountBalanceService;

class Create extends Component
{
    public $vendor;
    public $cable_name;
    public $iuc_number;
    public $cable_plan;

    public $customer;
    public $validate_action = false;
    
    public function mount()
    {
        $this->vendor = DataVendor::whereStatus(true)->first();
    }

    public function updatedCableName()
    {
        $this->cable_plan = null;
    }

    public function updatedIucNumber()
    {
        $this->validate_action = false;
        $this->customer = null;
    }

    public function submit()
    {
        $this->validate([
            'cable_name'    =>  'required|integer',
            'iuc_number'    =>  'required',
            'cable_plan'    =>  'required|integer'
        ]);

        if (!$this->validate_action) {
            $cableValidate = CableService::validateIUCNumber($this->vendor->id, $this->iuc_number, $this->cable_name); 

            if (!$cableValidate->status) {
                return $this->dispatch('error-toastr', ['message' => $cableValidate->message]);
            }
    
            if ($cableValidate->status) {
                $this->customer = $cableValidate->data->name;
                $this->validate_action = true;
                return $this->dispatch('success-toastr', ['message' => $cableValidate->message]);
            }

        }

        if ($this->validate_action) {
            $cableTransaction = CableService::create($this->vendor->id, $this->cable_name, $this->cable_plan, $this->iuc_number, $this->customer);

            if (!$cableTransaction->status) {
                return $this->dispatch('error-toastr', ['message' => $cableTransaction->message]);
            }
    
            if ($cableTransaction->status) {
                $this->dispatch('success-toastr', ['message' => $cableTransaction->message]);
                session()->flash('success',  $cableTransaction->message);
                return redirect()->route('dashboard');
            }
        }



        /*

            $cable = Cable::whereVendorId($this->vendor?->id)->whereCableId($this->cable_name)->first();
            
            $cable_plan = CablePlan::whereVendorId($this->vendor?->id)->whereCablePlanId($this->cable_plan)->first();
            
            $cableService = new CableService($this->vendor, $cable, $cable_plan, $this->iuc_number, $this->customer, Auth::user());

            $response = $cableService->CableSub();

            $response = json_decode($response);

            if (isset($response->error)) {
                // Insufficient User Balance Error
                return $this->dispatch('error-toastr', ['message' => "{$response->error} {$response->message}"]);
            }

            if (isset($response->response->error)) {
                // Insufficient API Wallet Balance Error
                return $this->dispatch('error-toastr', ['message' => "Unable to Perform Cable transaction. Please try again later."]);
            }

            if (isset($response->response->Status)) {

                if ($response->response->Status == 'successful') {

                    $accountBalance = new AccountBalanceService(Auth::user());
                    $accountBalance->transaction($response->transaction->amount);

                    CableTransaction::find($response->transaction->id)->update([
                        'balance_after'     =>    $accountBalance->getAccountBalance(),
                        'status'            =>    true,
                        'api_data_id'       =>    $response->response->ident ?? NULL,

                        // 'api_response'      =>    $response->response->api_response,
                    ]);

                    session()->flash('success', "Cable Purchased Successfully. You purchased {$response->transaction->cable_plan_name} ₦{$response->transaction->amount} for {$response->transaction->customer_name} ({$response->transaction->smart_card_number})");
                    return redirect()->route('dashboard');
                }

            }

            session()->flash('error', 'An error occurred during the Cable Payment request. Please try again later');
            return redirect()->to(url()->previous());

        */

    }

    public function validateIUCNumber()
    {
        $this->validate([
            'cable_name'    =>  'required|integer',
            'iuc_number'    =>  'required',
            'cable_plan'    =>  'required|integer'
        ]);


        /*
        try {

            $cable = Cable::whereVendorId($this->vendor?->id)->whereCableId($this->cable_name)->first();

            $response = Http::withHeaders([
                'Authorization' => "Token " . $this->vendor->token,
                'Content-Type' => 'application/json',
            ])->get(str_replace("/api", "", $this->vendor->api). "/ajax/validate_iuc/?smart_card_number={$this->iuc_number}&cablename={$cable->cable_name}");
    
            $response = $response->object();
            
            if (!$response->invalid) {
                $this->customer = $response->name;
                $this->validate_action = true;
                $this->dispatch('success-toastr', ['message' => "IUC validated. Click Continue to proceed payment."]);;
                return true;
            }

            return $this->dispatch('error-toastr', ['message' => 'Invalid IUC/SMARTCARD. Please provide a valid IUC/SMARTCARD']);
        
        } catch (\Exception $e) {
            
            return $this->dispatch('error-toastr', ['message' => 'Unable to Perform Cable transaction. Please check your network connection.']);

        }
        */
    }

    public function render()
    {
        return view('livewire.pages.utility.cable.create', [
            'cables'        =>  $this->vendor ? Cable::whereVendorId($this->vendor?->id)->whereStatus(true)->get() : [],
            'cable_plans'   =>  $this->vendor && $this->cable_name ? CablePlan::whereVendorId($this->vendor?->id)->whereCableId($this->cable_name)->whereStatus(true)->get() : []
        ]);
    }
}
