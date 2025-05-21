<?php

namespace App\Livewire\Component;

use App\Models\User;
use App\Services\Payment\MonnifyService;
use Livewire\Component;
use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\VirtualAccountServiceFactory;
use App\Actions\Automatic\Accounts\GenerateRemainingAccounts;

class UserAccountNumbersCard extends Component
{
    public User $user;

    public $virtualAccountServices = [];

    public $monnifyBankIds = ['50515', '035'];
    public function mount()
    {
        $this->virtualAccountServices = GenerateRemainingAccounts::$virtualAccountService;
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

            if (isset($service->status) && !$service->status) {
                $this->dispatch('error-toastr', ['message' => $service->message]);
                session()->flash('error', $service->message);
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

    public function deleteVirtualAccount(VirtualAccount $virtualAccount)
    {
        $deallocationService = (new MonnifyService)::deallocateReservedAccount($virtualAccount->reference);

        if ($deallocationService) {
            $virtualAccount->delete();
        }

        $message = $deallocationService ? 'Account De-allocated successfully.' : "Failed to delete virtual account. Please try again.";
        $type = $deallocationService ? 'success' : 'error';
        $this->dispatch("{$type}-toastr", ['message' => $message]);
        session()->flash($type, $message);
        return $this->redirect(url()->previous());
    }


    public function render()
    {
        return view('livewire.component.user-account-numbers-card');
    }
}
