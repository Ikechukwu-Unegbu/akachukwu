<?php

namespace App\Livewire\Pages\Utility\Electricity;

use Livewire\Component;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility\ElectricityTransaction;
use App\Services\Electricity\ElectricityService;

class Create extends Component
{
    public $vendor;
    public $meter_types = [1 => "PREPAID", 2 => "POSTPAID"];
    public $disco_name;
    public $meter_type;
    public $amount;
    public $meter_number;
    public $customer_phone_number;


    public function mount()
    {
        $this->vendor = DataVendor::whereStatus(true)->first();
    }

    public function submit()
    {
        $validated = $this->validate([
            'disco_name'             =>  'required|integer',
            'meter_type'             =>  'required|integer|in:1,2',
            'amount'                 =>  'required|numeric',
            'meter_number'           =>  'required',
            'customer_phone_number'  =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/']
        ]);        

        try {

            $electricity = Electricity::whereVendorId($this->vendor->id)->whereDiscoId($this->disco_name)->first();

            $electricityService = new ElectricityService($this->vendor, $electricity, $this->meter_number, $this->meter_type, Auth::user());

            $response = $electricityService->BillPayment($this->amount, $this->customer_phone_number);

            $response = json_decode($response);

            if (isset($response->error)) {
                // Insufficient User Balance Error
                return $this->dispatch('error-toastr', ['message' => "{$response->error} {$response->message}"]);
            }
    
            if (isset($response->response->error)) {
                // Insufficient API Wallet Balance Error
                return $this->dispatch('error-toastr', ['message' => "Unable to Perform Electricity transaction. Please try again later."]);
            }
    
            if (isset($response->response->Status)) {
    
                if ($response->response->Status == 'successful') {
    
                    $currentBalance = Auth::user()->account_balance;
                    $newBalance = $currentBalance - $response->transaction->amount;
    
                    Auth::user()->update(['account_balance' => $newBalance]);
    
                    ElectricityTransaction::find($response->transaction->id)->update([
                        'balance_after'     =>    $newBalance,
                        'status'            =>    true,
                        'api_data_id'       =>    $response->response->ident ?? NULL,
    
                        // 'api_response'      =>    $response->response->api_response,
                    ]);
    
                    session()->flash('success', "Bill Payment was Successful. You purchased ₦{$response->transaction->amount} for {$response->transaction->meter_type_name} to ({$response->transaction->meter_number})");
                    return redirect()->route('dashboard');
                }    
            }

            session()->flash('error', 'An error occurred during the Bill Payment request. Please try again later');
            return redirect()->to(url()->previous());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'An error occurred during the Bill Payment request. Please try again later');
            return redirect()->to(url()->previous());
        }
    }

    public function render()
    {
        return view('livewire.pages.utility.electricity.create', [
            'electricity' => $this->vendor ? Electricity::whereVendorId($this->vendor?->id)->whereStatus(true)->get() : []
        ]);
    }
}
