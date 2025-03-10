<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Traits\PaymentStatusTrait;
use App\Traits\RecordsBalanceChanges;

class Flutterwave extends Model
{
    use PaymentStatusTrait, RecordsBalanceChanges;
    protected $statusField = 'api_status';
    protected $table = 'flutterwave_transactions';
    protected $guarded = [];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setStatus($status)
    {
        $this->status = $status;
        $this->save();
    }

    public function setTransactionId($id)
    {
        $this->transaction_id = $id;
        $this->save();
    }
}
