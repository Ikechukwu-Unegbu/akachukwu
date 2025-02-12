<?php

namespace App\Livewire\Component;

use App\Models\User;
use Livewire\Component;
use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\VirtualAccountServiceFactory;
use App\Actions\Automatic\Accounts\GenerateRemainingAccounts;

class UserAccountNumbersCard extends Component
{


    public User $user;

    public $virtualAccountService;

    public function mount()
    {
        $this->virtualAccountService = GenerateRemainingAccounts::$virtualAccountService;
    }


    public function changeAccount($virtualAccountID, $bankCode, $user)
    {
        $user = User::find($user);
        $currentVirtualAccount = VirtualAccount::find($virtualAccountID);
        $activeGateway = PaymentGateway::where('id', $currentVirtualAccount->payment_id)->first();
        $virtualAccountFactory = VirtualAccountServiceFactory::make($activeGateway);
        $response = $virtualAccountFactory::createSpecificVirtualAccount($user, $virtualAccountID, $bankCode);

        if ($response->status === 'success') {
            session()->flash('message', $response->message);
        } else {
            session()->flash('error', $response->message);
        }

        $this->dispatch('accountChanged');
    }

    public function createVirtualAccount($bankCode, $userId)
    {
        try {

            $user = User::find($userId);

            $service = (new GenerateRemainingAccounts)->generateSpecificAccount($user, $bankCode);
    
            if (isset($service->status) && $service->status === true) {
                $this->dispatch('success-toastr', ['message' => $service->message]);
                session()->flash('success', $service->message);
                return $this->redirect(url()->previous());
            }

            $errorMessage = "Failed to create virtual account. Please try again.";
            $this->dispatch('error-toastr', ['message' => $errorMessage]);
            session()->flash('error', $errorMessage);
            return $this->redirect(url()->previous());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.component.user-account-numbers-card');
    }
}
