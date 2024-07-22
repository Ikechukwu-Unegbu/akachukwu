<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $guarded = [];

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Set all status values to false.
     *
     * @return void
     */
    public static function setAllStatusToFalse()
    {
        self::query()->update(['status' => false]);
    }

    /**
     * Set all virtual acct. status values to false.
     *
     * @return void
     */
    public static function setAllVAToFalse()
    {
        self::query()->update(['va_status' => false]);
    }
}
