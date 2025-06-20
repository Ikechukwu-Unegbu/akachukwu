<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CowrywiseNextOfKin extends Model
{
    protected $guarded = ['id'];

    public function account()
    {
        return $this->belongsTo(CowryWiseAccount::class);
    }
}
