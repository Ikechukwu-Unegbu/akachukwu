<?php

namespace App\Livewire\Pages\VirutalAccount;

use Livewire\Component;
use App\Services\Payment\VirtualAccountServiceFactory;
use Illuminate\Validation\ValidationException;


class Create extends Component
{
    public function virtualAccountAction()
    {

        if (empty(auth()->user()->phone)) return $this->dispatch('error-toastr', ['message' => "Opps! Your Mobile number is required to create a static account."]);

        $virtualAccountFactory = VirtualAccountServiceFactory::make();

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
