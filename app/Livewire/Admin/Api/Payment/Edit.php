<?php

namespace App\Livewire\Admin\Api\Payment;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\PaymentGateway;

class Edit extends Component
{
    public $payment;
    // #[Rule('required|string')]
    // public $name;
    #[Rule('required|string')]
    public $key;
    #[Rule('required|string')]
    public $public_key;
    #[Rule('required|string')]
    public $contract_code;
    #[Rule('required|boolean')]
    public $status;
    #[Rule('required|boolean')]
    public $va_status;


    public function mount(PaymentGateway $payment)
    {
        $this->payment = $payment;
        // $this->name = $this->payment->name;
        $this->key = $this->payment->key;
        $this->public_key = $this->payment->public_key;
        $this->contract_code = $this->payment->contract_code;
        $this->status = $this->payment->status ? true : false;
        $this->va_status = $this->payment->va_status ? true : false;
        $this->authorize('edit payment api');
    }

    public function update()
    {
        $validated = $this->validate([
            'key'             =>   'required|string',
            'public_key'      =>   'required|string',
            'contract_code'   =>   ($this->payment->name == 'Monnify')  ? 'required|numeric' : 'nullable',
            'status'          =>   'required|boolean',
            'va_status'       =>   'required|boolean',
        ]);

        // if ($this->status) $this->payment->setAllStatusToFalse();
        if ($this->va_status) $this->payment->setAllVAToFalse();

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
