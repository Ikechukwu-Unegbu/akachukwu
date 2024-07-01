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
    }

    public function submit()
    {
        $this->validate([
            'pin'   =>  'required|numeric|digits:4',
            'pin_confirmation'  =>  'required|same:pin'
        ]);
        
        $userPinService = UserPinService::createPin($this->pin, $this->pin_confirmation);

        if ($userPinService) {
            $this->dispatch('success-toastr', ['message' => "Pin created successfully"]);
            session()->flash('success',  "Pin created successfully");
            return redirect()->to(url()->previous());
        }
    }

    public function update()
    {
        $this->validate([
            'current_pin'   =>  'required|numeric|digits:4',
            'pin'   =>  'required|numeric|digits:4',
            'pin_confirmation'  =>  'required|same:pin'
        ]);

        $userPinService = UserPinService::updatePinWithCurrent($this->current_pin, $this->pin, $this->pin_confirmation);
        
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
