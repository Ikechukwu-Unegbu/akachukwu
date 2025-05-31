<?php

namespace App\Models\Utility;

use App\Models\User;
use App\Traits\HasTransactionType;
use Illuminate\Support\Str;
use App\Traits\HasStatusText;
use App\Models\Data\DataVendor;
use Spatie\Activitylog\LogOptions;
use App\Traits\RecordsBalanceChanges;
use App\Traits\GeneratesTransactionId;
use App\Traits\TransactionStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CableTransaction extends Model
{
    use HasFactory, TransactionStatusTrait, GeneratesTransactionId, RecordsBalanceChanges, HasStatusText, HasTransactionType;
    protected $guarded = [];
    protected $addsToBalance = false;
    protected $fillable = [
        'transaction_id',
        'user_id',
        'vendor_id',
        'cable_name',
        'cable_id',
        'cable_plan_name',
        'cable_plan_id',
        'smart_card_number',
        'customer_name',
        'amount',
        'balance_before',
        'balance_after',
        'balance_after_refund',
        'api_data_id',
        'api_response',
        'status',
        'discount'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor() : BelongsTo
    {
        return $this->belongsTo(DataVendor::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('transaction_id', 'LIKE', "%{$search}%")
                ->orWhere('cable_name', 'LIKE', "%{$search}%")
                ->orWhere('cable_plan_name', 'LIKE', "%{$search}%")
                ->orWhere('smart_card_number', 'LIKE', "%{$search}%")
                ->orWhere('customer_name', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('username', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%");
                });
        });
    }
}
