<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cable extends Model
{
    protected $guarded = [];

    public function plans() : HasMany
    {
        return $this->hasMany(CablePlan::class, 'cable_id', 'cable_id');
    }
}
