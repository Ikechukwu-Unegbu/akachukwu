<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Referral extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }


   // Relationship to the user who referred
   public function referrer()
   {
       return $this->belongsTo(User::class, 'referrer_id');
   }


    public function referredUser()
    {
        return $this->hasMany(User::class, Referral::class, 'referred_user_id');

    }





}
