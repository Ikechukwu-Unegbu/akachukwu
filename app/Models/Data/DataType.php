<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataType extends Model
{
    protected $guarded = [];

    public function dataPlans() : HasMany
    {
        return $this->hasMany(DataPlan::class, 'type_id');
    }
}
