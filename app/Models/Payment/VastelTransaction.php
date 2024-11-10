<?php

namespace App\Models\Payment;

use App\Traits\PaymentStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VastelTransaction extends Model
{
    use PaymentStatusTrait;
    
    use HasFactory;
}
