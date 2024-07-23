<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\PaymentGateway;
use App\Services\Payment\MonnifyService;
use App\Services\Payment\VirtualAccountServiceFactory;

class KycForm extends Component
{
    public $bvn;
    public $check_bvn_exists;

    public function mount()
    {
        $this->bvn = auth()->user()->bvn;
        $this->check_bvn_exists = auth()->user()->bvn;
    }

    protected $rules = [
        'bvn' => 'required|numeric|digits:11'
    ];

    public function submit()
    {
        $this->validate();

        if ($this->check_bvn_exists) return $this->dispatch('error-toastr', ['message' => "BVN is already linked to your account."]);
        if (!auth()->user()->virtualAccounts()->count()) return $this->dispatch('error-toastr', ['message' => "Unable to update BVN. Virtual account not found!"]);

    
        $activeGateway = PaymentGateway::find(auth()->user()->virtualAccounts()->first()->payment_id);
        $virtualAccountFactory = VirtualAccountServiceFactory::make($activeGateway);
        $kycService = $virtualAccountFactory::verifyKyc($this->bvn);

        // $kycService = MonnifyService::verifyKyc($this->bvn);

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
        return view('livewire.profile.kyc-form');
    }
}
