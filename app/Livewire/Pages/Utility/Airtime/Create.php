<?php

namespace App\Livewire\Pages\Utility\Airtime;

use Livewire\Component;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Models\Utility\AirtimeTransaction;
use App\Services\Account\AccountBalanceService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\Airtime\AirtimeService;

class Create extends Component
{
    public $network;
    public $vendor;
    public $amount;
    public $phone_number;

    public function mount()
    {
        $this->vendor = DataVendor::whereStatus(true)->first();
        $this->network = DataNetwork::whereVendorId($this->vendor?->id)->whereStatus(true)->first()?->network_id;
    }

    public function submit()
    {        
        $this->validate([
            'network'       =>  'required|integer',
            'amount'        =>  'required|numeric',
            'phone_number'  =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
        ]);

        try {

            $network = DataNetwork::whereVendorId($this->vendor->id)->whereNetworkId($this->network)->first();
            $airtimeServices = new AirtimeService($this->vendor, $network, Auth::user());

            $response = $airtimeServices->airtime(
                $this->amount,
                $this->phone_number
            );

            $response = json_decode($response);

            if (isset($response->error)) {
                // Insufficient User Balance Error
                return $this->dispatch('error-toastr', ['message' => "{$response->error} {$response->message}"]);
            }
    
            if (isset($response->response->error)) {
                // Insufficient API Wallet Balance Error
                return $this->dispatch('error-toastr', ['message' => 'An error occurred during the Airtime request. Please try again later']);
            }

            if (isset($response->response->Status)) {

                if ($response->response->Status == 'successful') {
                    
                    $accountBalance = new AccountBalanceService(Auth::user());
                    $accountBalance->transaction($this->amount);

    
                    AirtimeTransaction::find($response->transaction->id)->update([
                        'balance_after'     =>    $accountBalance->getAccountBalance(),
                        'status'            =>    true,
                        'api_data_id'       =>    $response->response->ident,

                        // 'api_response'      =>    $response->response->api_response,
                    ]);
    
                    session()->flash('success', "Airtime Purchased Successfully. You purchased {$network->name} ₦{$this->amount} for {$this->phone_number}");
                    return redirect()->route('dashboard');
                }

            }

            session()->flash('error', 'An error occurred during the Airtime Payment request. Please try again later');
            return redirect()->to(url()->previous());

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'An error occurred during the Airtime request. Please try again later');
            return redirect()->to(url()->previous());
        }

    }
    
    public function render()
    {
        return view('livewire.pages.utility.airtime.create', [
            'networks'      =>  $this->vendor ? DataNetwork::whereVendorId($this->vendor->id)->whereStatus(true)->get() : [],
        ]);
    }
}
