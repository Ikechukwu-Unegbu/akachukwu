<?php

namespace App\Models\Payment;

use App\Models\User;
use App\Traits\HasStatusText;
use App\Traits\HasTransactionType;
use App\Traits\PaymentStatusTrait;
use App\Traits\RecordsBalanceChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonnifyTransaction extends Model
{
    use PaymentStatusTrait, RecordsBalanceChanges, HasStatusText, HasTransactionType;
    protected $statusField = 'api_status';
    protected $addsToBalance = true;
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
}
