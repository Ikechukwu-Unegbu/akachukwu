<?php

namespace App\Models\Payment;

use App\Models\User;
use App\Traits\PaymentStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayVesselTransaction extends Model
{
    use PaymentStatusTrait;
    
    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
