<?php

namespace App\Models\Payment;

use App\Models\User;
use App\Traits\HasStatusText;
use App\Traits\HasTransactionType;
use App\Traits\PaymentStatusTrait;
use App\Traits\RecordsBalanceChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VastelTransaction extends Model
{
    use PaymentStatusTrait, RecordsBalanceChanges, HasStatusText, HasTransactionType;
    protected $statusField = 'api_status';
    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
