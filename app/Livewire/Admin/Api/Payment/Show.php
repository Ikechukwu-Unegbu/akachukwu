<?php

namespace App\Livewire\Admin\Api\Payment;

use App\Models\PaymentGateway;
use Livewire\Component;

class Show extends Component
{
    public $payment;

    public function mount(PaymentGateway $payment)
    {
        $this->payment = $payment;
    }

    public function render()
    {
        return view('livewire.admin.api.payment.show');
    }
}