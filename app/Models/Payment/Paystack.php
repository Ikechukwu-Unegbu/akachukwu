<?php

namespace App\Models\Payment;

use App\Traits\HasStatusText;
use App\Traits\HasTransactionType;
use App\Traits\PaymentStatusTrait;
use App\Traits\RecordsBalanceChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paystack extends Model
{
    use PaymentStatusTrait, RecordsBalanceChanges, HasStatusText, HasTransactionType;

    protected $table = 'paystack_transactions';
    protected $addsToBalance = true;
    protected $guarded = [];

    public function setStatus($status)
    {
        $this->status = $status;
        $this->save();
    }
}
