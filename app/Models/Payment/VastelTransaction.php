<?php

namespace App\Models\Payment;

use App\Models\User;
use App\Traits\PaymentStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VastelTransaction extends Model
{
    use PaymentStatusTrait;
    
    use HasFactory;

    public function sender()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
