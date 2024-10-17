<?php

namespace App\Models\Utility;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cable extends Model
{
    protected $guarded = [];

    public function plans() : HasMany
    {
        return $this->hasMany(CablePlan::class, 'cable_id', 'cable_id');
    }

    public function getImageUrlAttribute()
    {
        if (File::exists(public_path('images/' . Str::lower($this->cable_name) . '.png'))) {
            return secure_asset('images/' . Str::lower($this->cable_name) . '.png');
        }
        return "https://placehold.co/400";
    }
}
