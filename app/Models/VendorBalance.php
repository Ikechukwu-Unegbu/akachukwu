<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorBalance extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $uuid = Str::uuid();
            } while (self::where('uuid', $uuid)->exists());
            $model->uuid = $uuid;
        });
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
