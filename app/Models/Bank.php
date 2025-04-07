<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $guarded = [];

    public function scopeIsMonnify($query)
    {
        return $query->where(['type' => 'monnify', 'status' => true])->orderBy('name');
    }

    public function scopeIsPalmPay($query)
    {
        return $query->where(['type' => 'palmpay', 'status' => true])->orderBy('name');
    }
}
