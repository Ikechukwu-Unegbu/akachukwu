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

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('api', 'LIKE', "%{$search}%");
        });
    }
}
