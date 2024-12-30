<?php

namespace App\Models\Payment;

use App\Traits\PaymentStatusTrait;
use App\Traits\RecordsBalanceChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paystack extends Model
{
    use PaymentStatusTrait, RecordsBalanceChanges;
    
    protected $table = 'paystack_transactions';
    protected $guarded = [];

    public function setStatus($status)
    {
        $this->status = $status;
        $this->save();
    }
}
