<?php

namespace App\Livewire\Admin\Api\Payment;

use App\Models\PaymentGateway;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;
    
    public function render()
    {
        return view('livewire.admin.api.payment.index', [
            'payments' =>    PaymentGateway::search($this->search)->paginate($this->perPage)
        ]);
    }
}
