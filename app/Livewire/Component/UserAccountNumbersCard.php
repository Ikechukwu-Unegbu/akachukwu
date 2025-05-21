<?php

namespace App\Livewire\Component;

use App\Models\User;
use Livewire\Component;
use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\VirtualAccountServiceFactory;
use App\Actions\Automatic\Accounts\GenerateRemainingAccounts;
use App\Helpers\ActivityConstants;
use App\Helpers\GeneralHelpers;

use Illuminate\Support\Facades\Auth;
use App\Services\Admin\Activity\ActivityLogService;

class UserAccountNumbersCard extends Component
{
    public User $user;

    public $virtualAccountServices = [];

    public function mount()
    {
        $this->virtualAccountServices = GenerateRemainingAccounts::$virtualAccountService;
    }


    public function changeAccount($virtualAccountID, $bankCode, $user)
    {
        $user = User::find($user);
        $currentVirtualAccount = VirtualAccount::find($virtualAccountID);

        ActivityLogService::log([
            'activity'=>"Change",
            'description'=>"Attempting to Change Virtual Account",
            'type'=>ActivityConstants::VIRTUALACCOUNT,
            'resource_owner_id'=>$user->id, 
            'resource'=>serialize($currentVirtualAccount)
        ]);

    
        $activeGateway = PaymentGateway::where('id', $currentVirtualAccount->payment_id)->first();
        $virtualAccountFactory = VirtualAccountServiceFactory::make($activeGateway);
        $response = $virtualAccountFactory::createSpecificVirtualAccount($user, $virtualAccountID, $bankCode);

        if ($response->status === 'success') {
            ActivityLogService::log([
                'activity'=>"Change",
                'description'=>"Succeeded to Change Virtual Account",
                'type'=>ActivityConstants::VIRTUALACCOUNT,
                'resource_owner_id'=>$user->id, 
                'resource'=>serialize($currentVirtualAccount)
                
            ]);
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

            ActivityLogService::log([
                'activity'=>"Change",
                'description'=>"Attempting to Create Virtual Account",
                'type'=>ActivityConstants::VIRTUALACCOUNT,
                'resource_owner_id'=>$user->id, 
                // 'resource'=>serialize($currentVirtualAccount)
            ]);

            $service = (new GenerateRemainingAccounts)->generateSpecificAccount($user, $bankCode);
    
            if (isset($service->status) && $service->status === true) {

                ActivityLogService::log([
                    'activity'=>"Change",
                    'description'=>"Succeeded to Create Virtual Account",
                    'type'=>ActivityConstants::VIRTUALACCOUNT,
                    'resource_owner_id'=>$user->id, 
                    // 'resource'=>serialize($currentVirtualAccount)
                ]);
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


    public function render()
    {
        return view('livewire.component.user-account-numbers-card');
    }
}
