<?php

namespace App\Livewire\Pages\VirutalAccount;

use Livewire\Component;
use App\Models\PaymentGateway;
use Illuminate\Validation\ValidationException;
use App\Services\Payment\VirtualAccountServiceFactory;


class Create extends Component
{
    public function virtualAccountAction()
    {

        if (empty(auth()->user()->phone)) return $this->dispatch('error-toastr', ['message' => "Opps! Your Mobile number is required to create a static account."]);

        $activeGateway = PaymentGateway::where('va_status', true)->first();
        $virtualAccountFactory = VirtualAccountServiceFactory::make($activeGateway);
        $response = $virtualAccountFactory::createVirtualAccount(auth()->user());

        if (auth()->user()->virtualAccounts->count()) {
            $this->dispatch('success-toastr', ['message' => $response->message]);
            session()->flash('success', $response->message);
            return redirect()->to(url()->previous());
        }

        return $this->dispatch('error-toastr', ['message' => "Opps! Unable to create static account. Please try again"]);
    }

    public function render()
    {
        return view('livewire.pages.virutal-account.create');
    }
}
