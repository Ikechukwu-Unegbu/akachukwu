<?php

namespace App\Models\Utility;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use Spatie\Activitylog\LogOptions;
use App\Traits\ThrottlesTransactions;
use App\Traits\GeneratesTransactionId;
use App\Traits\TransactionStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AirtimeTransaction extends Model
{
    use LogsActivity, TransactionStatusTrait, GeneratesTransactionId; 
    protected $throttleActionName = 'airtime_purchase';
    protected $guarded = [];
    protected $fillable = [
        'transaction_id',
        'user_id',
        'vendor_id',
        'network_id',
        'network_name',
        'amount',
        'mobile_number',
        'balance_before',
        'balance_after',
        'api_data_id',
        'api_response',
        'status',
        'discount'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 
        'transaction_id',
        'user_id',
        'vendor_id',
        'network_id',
        'network_name',
        'amount',
        'mobile_number',
        'balance_before',
        'balance_after',
        'balance_after_refund',
        'api_data_id',
        'api_response',
        'status',
        'discount' ]);
        // Chain fluent methods for configuration options
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = auth()->user()->id;
        });
    }

    public function vendor() : BelongsTo
    {
        return $this->belongsTo(DataVendor::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function network() : BelongsTo
    {
        return $this->belongsTo(DataNetwork::class, 'network_id', 'network_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('transaction_id', 'LIKE', "%{$search}%")
                ->orWhere('mobile_number', 'LIKE', "%{$search}%")
                ->orWhere('network_name', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('username', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                });
        });
    }
}
