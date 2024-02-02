<?php

namespace App\Models\Utility;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AirtimeTransaction extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->transaction_id = static::generateUniqueId();
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

    public static function generateUniqueId(): string
    {
        return Str::slug(date('Ymd').microtime().'-airtime-'.Str::random(10).microtime().Str::random(4));
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
                            ->orWhere('email', 'LIKE', "%{$search}%");
                });
        });
    }
}
