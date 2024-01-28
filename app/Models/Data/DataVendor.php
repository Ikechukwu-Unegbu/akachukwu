<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataVendor extends Model
{
    protected $guarded = [];

    public function networks() : HasMany
    {
        return $this->hasMany(DataNetwork::class, 'vendor_id');
    }
}
