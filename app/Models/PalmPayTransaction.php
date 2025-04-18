<?php

namespace App\Models;

use App\Traits\HasTransactionType;
use App\Traits\PaymentStatusTrait;
use App\Traits\RecordsBalanceChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PalmPayTransaction extends Model
{
    use PaymentStatusTrait, RecordsBalanceChanges, HasTransactionType;

    protected $addsToBalance = true;
    protected $statusField = 'api_status';
    protected $guarded = ['id'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
