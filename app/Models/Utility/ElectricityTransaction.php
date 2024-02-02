<?php

namespace App\Models\Utility;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Data\DataVendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ElectricityTransaction extends Model
{
    protected $guarded = [];

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
        return Str::slug(date('Ymd').microtime().'-electricity-'.Str::random(10).microtime().Str::random(4));
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('transaction_id', 'LIKE', "%{$search}%")
                ->orWhere('disco_name', 'LIKE', "%{$search}%")
                ->orWhere('meter_number', 'LIKE', "%{$search}%")
                ->orWhere('meter_type_name', 'LIKE', "%{$search}%")
                ->orWhere('customer_name', 'LIKE', "%{$search}%")
                ->orWhere('customer_mobile_number', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('username', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                });
        });
    }
    
}
