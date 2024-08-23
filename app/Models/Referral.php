<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $guarded = [];


   // Relationship to the user who referred
   public function referrer()
   {
       return $this->belongsTo(User::class, 'referrer_id');
   }


    public function referredUser()
    {
        return $this->hasMany(User::class, Referral::class, 'referred_user_id');
                    // ->select('users.name', 'users.username');
    }





}
