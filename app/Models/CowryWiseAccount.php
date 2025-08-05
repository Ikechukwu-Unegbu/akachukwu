<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CowryWiseAccount extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_verified' => 'boolean',
        'terms_of_use_accepted' => 'boolean',
        'is_proprietary' => 'boolean',
        'date_joined' => 'datetime',
        // 'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function banks()
    {
        return $this->hasMany(CowrywiseBank::class);
    }

    public function nextOfKin()
    {
        return $this->hasOne(CowrywiseNextOfKin::class);
    }

    public function identities()
    {
        return $this->hasMany(CowryWiseIdentity::class);
    }

    public function wallets()
    {
        return $this->hasMany(CowryWallet::class, 'cowry_wise_account_id')->where('currency', config('app.currency'));
    }
}
