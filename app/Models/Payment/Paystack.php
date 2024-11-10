<?php

namespace App\Models\Payment;

use App\Traits\PaymentStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paystack extends Model
{
    use PaymentStatusTrait;
    
    protected $table = 'paystack_transactions';
    protected $guarded = [];

    public function setStatus($status)
    {
        $this->status = $status;
        $this->save();
    }
}
