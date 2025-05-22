<?php

namespace App\Models;

use App\Traits\HasTransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoneyTransfer extends Model
{
    use HasTransactionType;
    protected $guarded = [];
    protected $addsToBalance = false;

    protected $title = 'transfer';
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'recipient')->withTrashed();
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_id', 'LIKE', "%{$search}%")
                    ->orWhere('trx_ref', 'LIKE', "%{$search}%")
                    ->orWhereHas('sender', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('username', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('receiver', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('username', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        });
    }

    public function scopeIsInternal($query)
    {
        return $query->where('type', 'internal');
    }

    public function scopeIsExternal($query)
    {
        return $query->where('type', 'external');
    }
}
