<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PalmPayBank extends Model
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model) {
            do {
                $uuid = Str::uuid();
            } while(self::where('uuid', $uuid)->exists());
            $model->uuid = $uuid;
        });
    }
}
