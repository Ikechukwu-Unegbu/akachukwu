<?php

namespace App\Livewire\Component;

use App\Models\PaymentGateway;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Services\Payment\VirtualAccountServiceFactory;
use Livewire\Component;

class UserAccountNumbersCard extends Component
{


    public User $user;


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


    public function render()
    {
        return view('livewire.component.user-account-numbers-card');
    }
}
