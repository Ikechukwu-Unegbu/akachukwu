<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paystack extends Model
{
    protected $table = 'paystack_transactions';
    protected $guarded = [];

    public function setStatus($status)
    {
        $this->status = $status;
        $this->save();
    }
}
