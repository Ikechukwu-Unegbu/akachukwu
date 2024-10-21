<?php

namespace App\Livewire\Otp;

use Livewire\Component;
use App\Services\OTPService;
use Livewire\Attributes\Rule;
use App\Notifications\WelcomeEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class Verify extends Component
{
    #[Rule('required')]
    public $otp;
 
    public function mount()
    {
        $this->otp = array_fill(1, 4, '');
    }

    public function updateOtp($index, $value)
    {
        $this->otp[$index] = $value;
    }

    public function submit(OTPService $otpService)
    {
        if (!is_array($this->otp)) {
            $otp = (array) $this->otp;
        }
        $otp = implode('', $this->otp);
        $verify = $otpService->verifyOTP(auth()->user(), $otp);
        if (!$verify) {
            throw ValidationException::withMessages([
                'otp' => __('The OTP provided is not valid or expired.'),
            ]);
        }

        $this->dispatch('success-toastr', ['message' => 'OTP verified successfully.']);
        session()->flash('success', 'OTP verified successfully.');
        return redirect()->route('dashboard');
    }

    public function resend(OTPService $otpService)
    {
        try {
            $user = auth()->user();
            $otp = $otpService->generateOTP($user);
            Notification::sendNow($user, new WelcomeEmail($otp, $user));
            return ($otp) ?
                $this->dispatch('success-toastr', ['message' => 'OTP sent successfully.']) :
                $this->dispatch('error-toastr', ['message' => 'Opps! Unable to send OTP. Please try again later.']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->dispatch('error-toastr', ['message' => 'Opps! Unable to send OTP. Please try again later.']);
        }
    }

    public function render()
    {
        return view('livewire.otp.verify');
    }
}
