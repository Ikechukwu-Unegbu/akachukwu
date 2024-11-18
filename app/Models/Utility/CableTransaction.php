<?php

namespace App\Models\Utility;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Data\DataVendor;
use Spatie\Activitylog\LogOptions;
use App\Traits\TransactionStatusTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CableTransaction extends Model
{
    use LogsActivity, HasFactory, TransactionStatusTrait; 
    protected $guarded = [];
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
            'cable_name',
            'cable_id',
            'cable_plan_name',
            'cable_plan_id',
            'smart_card_number',
            'customer_name',
            'amount',
            'balance_before',
            'balance_after',
            'api_data_id',
            'api_response',
            'status',
            'discount'
        ]);
        // Chain fluent methods for configuration options
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->transaction_id = static::generateUniqueId();
        });
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor() : BelongsTo
    {
        return $this->belongsTo(DataVendor::class);
    }

    public static function generateUniqueId(): string
    {
        return Str::slug(date('YmdHi').'-cable-'.Str::random(10).microtime().Str::random(4));
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
