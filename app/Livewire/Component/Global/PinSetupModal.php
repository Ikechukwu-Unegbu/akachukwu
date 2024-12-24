<?php

namespace App\Livewire\Component\Global;

use App\Services\Account\UserPinService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PinSetupModal extends Component
{
    public $pin = [];
    public $pin_confirmation = [];
    public $hasPinSetup;

    public function mount()
    {
        $user = Auth::user();
        $this->hasPinSetup = (is_null($user->pin) || empty($user->pin)) ? true : false;
    }

    public function updatePin($index, $value)
    {
        $this->pin[$index] = $value;
    }

    public function updatePinConfirmation($index, $value)
    {
        $this->pin_confirmation[$index] = $value;
    }

    public function update()
    {
        if (!is_array($this->pin)) $this->pin = (array) $this->pin;
        if (!is_array($this->pin_confirmation)) $this->pin_confirmation = (array) $this->pin_confirmation;
      
        $this->validate([
            'pin' => ['required', 'array', 'size:4'],
            'pin.*' => ['required', 'numeric', 'digits:1'],
            'pin_confirmation' => ['required', 'array', 'size:4'],
            'pin_confirmation.*' => ['required', 'numeric', 'digits:1'],
        ]);
        
        $pin = implode('', $this->pin);
        $pin_confirmation = implode('', $this->pin_confirmation);

        if ($pin !== $pin_confirmation) {
            $this->addError('pin_confirmation', 'The confirmation PIN does not match.');
            return;
        }
        
        $userPinService = UserPinService::createPin($pin, $pin_confirmation);

        if ($userPinService) {
            $this->dispatch('success-toastr', ['message' => "Pin setup successfully"]);
            session()->flash('success',  "Pin setup successfully");
            return redirect()->to(url()->previous());
        }
    }

    public function render()
    {
        return view('livewire.component.global.pin-setup-modal');
    }
}
