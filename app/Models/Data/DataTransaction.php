<?php

namespace App\Models\Data;

use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataTransaction extends Model
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
        return Str::slug(date('Ymd').microtime().'-data-'.Str::random(10).Str::random(4));
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
