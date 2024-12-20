<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTransfer extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'recipient');
    }

}
