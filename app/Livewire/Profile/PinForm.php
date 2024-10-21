<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Services\Account\UserPinService;
use Livewire\Component;

class PinForm extends Component
{
    public $user;
    public $pin;
    public $current_pin;
    public $pin_confirmation;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->pin = array_fill(1, 4, '');
        $this->current_pin = array_fill(1, 4, '');
        $this->pin_confirmation = array_fill(1, 4, '');
    }

    public function updatePin($index, $value)
    {
        $this->pin[$index] = $value;
    }

    public function updateCurrentPin($index, $value)
    {
        $this->current_pin[$index] = $value;
    }

    public function updatePinConfirmation($index, $value)
    {
        $this->pin_confirmation[$index] = $value;
    }

    public function submit()
    {
        if (!is_array($this->pin)) $pin = (array) $this->pin;
        if (!is_array($this->pin_confirmation)) $pin_confirmation = (array) $this->pin_confirmation;
        $pin = implode('', $this->pin);
        
        $pin_confirmation = implode('', $this->pin_confirmation);

        $userPinService = UserPinService::createPin($pin, $pin_confirmation);

        if ($userPinService) {
            $this->dispatch('success-toastr', ['message' => "Pin created successfully"]);
            session()->flash('success',  "Pin created successfully");
            return redirect()->to(url()->previous());
        }
    }

    public function update()
    {
        if (!is_array($this->pin)) $pin = (array) $this->pin;
        if (!is_array($this->pin)) $current_pin = (array) $this->current_pin;
        if (!is_array($this->pin_confirmation)) $pin_confirmation = (array) $this->pin_confirmation;
        $pin = implode('', $this->pin);
        $pin_confirmation = implode('', $this->pin_confirmation);
        $current_pin = implode('', $this->current_pin);

        $userPinService = UserPinService::updatePinWithCurrent($current_pin, $pin, $pin_confirmation);
        
        if ($userPinService) {
            $this->dispatch('success-toastr', ['message' => "Pin updated successfully"]);
            session()->flash('success',  "Pin updated successfully");
            return redirect()->to(url()->previous());
        }
    }

    public function render()
    {
        return view('livewire.profile.pin-form');
    }
}
