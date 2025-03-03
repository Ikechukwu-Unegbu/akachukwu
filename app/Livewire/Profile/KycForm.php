<?php

namespace App\Livewire\Profile;

use App\Models\Bank;
use Livewire\Component;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Storage;
use App\Services\Payment\MonnifyService;
use App\Services\Payment\VirtualAccountServiceFactory;

class KycForm extends Component
{
    public $bvn;
    public $nin;
    public $dob;
    public $account_number;
    public $bank;
    public $check_bvn_exists;

    public function mount()
    {
        $this->bvn = auth()->user()->bvn;
        $this->nin = auth()->user()->nin;
        $this->check_bvn_exists = (auth()->user()->bvn || auth()->user()->nin) ? true : false;
    }

    protected $rules = [
        'bvn'            => 'required|numeric|digits:11|unique:users,bvn',
        'account_number' => 'required|numeric|digits:10',
        'bank'           => 'required'
    ];

    public function verifyBvn()
    {
        $this->validate();
        return $this->verification('BVN');
    }

    public function verifyNin()
    {
        $this->validate([
            'nin'  => 'required|numeric|digits:11|unique:users,nin',
            'dob'  => 'required|date',
        ]);
        return $this->verification('NIN');
    }

    protected function verification($type)
    {
        if ($this->check_bvn_exists)
            return $this->dispatch('error-toastr', ['message' => "{$type} is already linked to your account."]);

        // if (!auth()->user()->virtualAccounts()->count()) 
        //     return $this->dispatch('error-toastr', ['message' => "Unable to update {$type}. Virtual account not found!"]);


        $activeGateway = PaymentGateway::where('name', 'Monnify')->firstOrFail();
        $virtualAccountFactory = VirtualAccountServiceFactory::make($activeGateway);
        if ($type === 'BVN') {
            $kycService = $virtualAccountFactory::verifyBvn($this->bvn, $this->bank, $this->account_number);
        }

        if ($type === 'NIN') {
            $kycService = $virtualAccountFactory::verifyNin($this->nin, $this->dob);
        }
        
        if (!$kycService->status) {
            return $this->dispatch('error-toastr', ['message' => $kycService->message]);
        }
        if ($kycService->status) {
            $this->dispatch('success-toastr', ['message' => $kycService->message]);
            session()->flash('success',  $kycService->message);
            return redirect()->to(url()->previous());
        }
    }

    public function render()
    {
        return view('livewire.profile.kyc-form', [
            'banks'  => Bank::where('type', 'monnify')->where('status', true)->orderBy('name')->get()
        ]);
    }
}
