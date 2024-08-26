<?php

namespace App\Models\Data;

use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DataTransaction extends Model
{
    use LogsActivity; 
    protected $guarded = [];

    protected $fillable = [
        'transaction_id',
        'user_id',
        'vendor_id',
        'network_id',
        'type_id',
        'data_id',
        'amount',
        'size',
        'validity',
        'mobile_number',
        'balance_before',
        'balance_after',
        'plan_network',
        'plan_name',
        'plan_amount',
        'api_data_id',
        'api_response',
        'status',
    
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'transaction_id',
        'user_id',
        'vendor_id',
        'network_id',
        'type_id',
        'data_id',
        'amount',
        'size',
        'validity',
        'mobile_number',
        'balance_before',
        'balance_after',
        'plan_network',
        'plan_name',
        'plan_amount',
        'api_data_id',
        'api_response',
        'status',]);
        // Chain fluent methods for configuration options
    }


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->transaction_id = static::generateUniqueId();
        });
    }

    public function network() : BelongsTo
    {
        return $this->belongsTo(DataNetwork::class, 'network_id', 'network_id');

    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    
    public function vendor() : BelongsTo
    {
        return $this->belongsTo(DataVendor::class);
    }

    public function data_plan() : BelongsTo
    {
        return $this->belongsTo(DataPlan::class, 'data_id', 'data_id');
    }

    public function data_type() : BelongsTo
    {
        return $this->belongsTo(DataType::class, 'type_id');
    }

    public static function generateUniqueId(): string
    {
        return Str::slug(date('YmdHi').'-data-'.Str::random(10).Str::random(4));
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('transaction_id', 'LIKE', "%{$search}%")
                ->orWhere('plan_network', 'LIKE', "%{$search}%")
                ->orWhere('mobile_number', 'LIKE', "%{$search}%")
                ->orWhere('plan_name', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('username', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                });
        });
    }

}
