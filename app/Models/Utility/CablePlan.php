<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CablePlan extends Model
{
    protected $guarded = [];

    public function cable() : BelongsTo
    {
        return $this->belongsTo(Cable::class, 'cable_id', 'cable_id');
    }
}
