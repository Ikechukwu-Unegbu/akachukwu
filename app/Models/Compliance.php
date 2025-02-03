<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compliance extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->user_id = Auth::id() ?? NULL;
            do {
                $uuid = Str::uuid();
            } while (self::where('uuid', $uuid)->exists());
            $model->uuid = $uuid;
        });
    }

    public static function payload($payload, $bvn = null, $nin = null)
    {
        return self::create([
            'meta' => $payload,
            'bvn' => $bvn,
            'nin' => $nin
        ]);
    }
}
