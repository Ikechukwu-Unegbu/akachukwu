<?php

namespace App\Models\Payment;

use App\Models\User;
use App\Traits\HasStatusText;
use App\Traits\HasTransactionType;
use App\Traits\PaymentStatusTrait;
use App\Traits\RecordsBalanceChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayVesselTransaction extends Model
{
    use PaymentStatusTrait, RecordsBalanceChanges, HasStatusText, HasTransactionType;
    protected $statusField = 'api_status';
    protected $addsToBalance = true;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
