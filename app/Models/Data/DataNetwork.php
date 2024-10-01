<?php

namespace App\Models\Data;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function logo()
    {
        if (File::exists(public_path('images/' . Str::lower($this->name) . '.png'))) {
            return secure_url('images/' . Str::lower($this->name) . '.png');
        }

        return "https://via.placeholder.com/24x24";
    }

}
