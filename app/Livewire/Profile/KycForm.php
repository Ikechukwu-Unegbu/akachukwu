<?php

namespace App\Livewire\Profile;

use App\Services\Payment\MonnifyService;
use Livewire\Component;

class KycForm extends Component
{
    public $bvn;
    public $check_bvn_exists;

    public function mount()
    {
        $this->bvn = auth()->user()->virtualAccounts->first()?->bvn;
        $this->check_bvn_exists = auth()->user()->virtualAccounts()->whereNotNull('bvn')->count();
    }

    protected $rules = [
        'bvn' => 'required|numeric|digits:11'
    ];

    public function submit()
    {
        $this->validate();

        if ($this->check_bvn_exists > 0) {
            return $this->dispatch('error-toastr', ['message' => "BVN is already linked to your account."]);
        }

        $kycService = MonnifyService::verifyKyc($this->bvn);

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
