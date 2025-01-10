<?php

namespace App\Models\Payment;

use App\Models\User;
use App\Traits\PaymentStatusTrait;
use App\Traits\RecordsBalanceChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayVesselTransaction extends Model
{
    use PaymentStatusTrait, RecordsBalanceChanges;
    protected $statusField = 'api_status';
    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
