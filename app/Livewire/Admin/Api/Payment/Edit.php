<?php

namespace App\Livewire\Admin\Api\Payment;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\PaymentGateway;

class Edit extends Component
{
    public $payment;
    #[Rule('required|string')]
    public $name;
    #[Rule('required|string')]
    public $key;
    #[Rule('required|boolean')]
    public $status;


    public function mount(PaymentGateway $payment)
    {
        $this->payment = $payment;
        $this->name = $this->payment->name;
        $this->key = $this->payment->key;
        $this->status = $this->payment->status ? true : false;
    }

    public function update()
    {
        $validated = $this->validate();
        
        $this->payment->update($validated);

        $this->dispatch('success-toastr', ['message' => 'Payment Gateway Updated Successfully']);
        session()->flash('success', 'Payment Gateway Updated Successfully');
        return redirect()->to(route('admin.api.payment'));
    }
    
    public function render()
    {
        return view('livewire.admin.api.payment.edit');
    }
}
