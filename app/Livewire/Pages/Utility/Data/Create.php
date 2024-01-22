<?php

namespace App\Livewire\Pages\Utility\Data;

use Livewire\Component;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Rules\PhoneNumberRule;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Services\Data\DataService;
use App\Models\Data\DataTransaction;
use Illuminate\Support\Facades\Auth;
use App\Services\Account\AccountBalanceService;

class Create extends Component
{
    public $network;
    public $vendor;
    public $dataType;
    public $phone_number;
    public $amount;
    public $plan;


    public function mount()
    {
        $this->vendor = DataVendor::whereStatus(true)->first();
        $this->network = DataNetwork::whereVendorId($this->vendor?->id)->whereStatus(true)->first()?->network_id;
    }

    public function updatedPlan()
    {
        $this->amount = DataPlan::whereVendorId($this->vendor?->id)->whereNetworkId($this->network)->whereDataId($this->plan)->first()?->amount;
        $this->amount = number_format($this->amount, 1);
    }

    public function updatedDataType()
    {
        $this->plan = null;
        $this->amount = null;
    }

    public function submit()
    {
        $this->validate([
            'network'       =>  'required|integer',
            'dataType'      =>  'required|integer',
            'plan'          =>  'required|integer',
            'phone_number'  =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
        ]);

        $dataServices = new DataService;
        $network = DataNetwork::whereVendorId($this->vendor->id)->whereNetworkId($this->network)->first();
        $plan = DataPlan::whereVendorId($this->vendor->id)->whereNetworkId($this->network)->whereDataId($this->plan)->first();
        $user = auth()->user();
        $ClientResponse = $dataServices->data(
            $this->vendor,
            $network,
            DataType::whereVendorId($this->vendor->id)->whereNetworkId($this->network)->whereId($this->dataType)->first(),
            $plan,
            $this->phone_number,
            $user
        );

        $ClientResponse = json_decode($ClientResponse);

        if (isset($ClientResponse->error)) {
            return $this->dispatch('error-toastr', ['message' => "{$ClientResponse->error} {$ClientResponse->message}"]);
        }

        if (isset($ClientResponse->response->error)) {
            // Insufficient API Wallet Balance Error
            return $this->dispatch('error-toastr', ['message' => "An error occurred during the Data request. Please try again later"]);
        }

        if (isset($ClientResponse->response->Status)) {
            if ($ClientResponse->response->Status == 'successful') {
                $accountBalance = new AccountBalanceService(Auth::user());
                $accountBalance->transaction($plan->amount);

                DataTransaction::find($ClientResponse->transaction->id)->update([
                    'balance_after'     =>    $accountBalance->getAccountBalance(),
                    'status'            =>    true,
                    'plan_network'      =>    $ClientResponse->response->plan_network,
                    'plan_name'         =>    $ClientResponse->response->plan_name,
                    'plan_amount'       =>    $ClientResponse->response->plan_amount,
                    'api_data_id'       =>    $ClientResponse->response->ident,
                    'api_response'      =>    $ClientResponse->response->api_response,
                ]);

                session()->flash('success', "Data Purchased Successfully. You purchased {$network->name} {$plan->size} ₦{$plan->amount} for {$this->phone_number}");
                return redirect()->route('dashboard');
            }
        }

        session()->flash('error', 'An error occurred during the Data Payment request. Please try again later');
        return redirect()->to(url()->previous());
    }

    public function render()
    {
        return view('livewire.pages.utility.data.create', [

            'networks'      =>  $this->vendor ? DataNetwork::whereVendorId($this->vendor->id)->whereStatus(true)->get() : [],
            'dataTypes'     =>  $this->vendor && $this->network ? DataType::whereVendorId($this->vendor->id)->whereNetworkId($this->network)->whereStatus(true)->get() : [],
            'plans'         =>  $this->vendor && $this->network && $this->dataType ? DataPlan::with('type')->whereVendorId($this->vendor->id)->whereNetworkId($this->network)->whereTypeId($this->dataType)->whereStatus(true)->get() : []
        ]);
    }
}
