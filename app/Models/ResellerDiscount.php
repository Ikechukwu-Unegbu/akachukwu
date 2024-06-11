<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerDiscount extends Model
{
    protected $guarded = [];

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('type', 'LIKE', "%{$search}%");
        });
    }

    public function editRoute()
    {
        return route('admin.transaction.reseller.edit', $this->id);
    }

    public function deleteRoute()
    {
        return route('admin.transaction.reseller.delete', $this->id);
    }
}
