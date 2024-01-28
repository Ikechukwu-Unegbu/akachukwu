<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataNetwork extends Model
{
    protected $guarded = [];

    public function dataTypes() : HasMany
    {
        return $this->hasMany(DataType::class, 'network_id', 'network_id');
    }

    public function dataPlans() : HasMany
    {
        return $this->hasMany(DataPlan::class, 'network_id', 'network_id');
    }

}
