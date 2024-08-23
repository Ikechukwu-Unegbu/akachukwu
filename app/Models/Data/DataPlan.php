<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPlan extends Model
{
    protected $guarded = [];

    
    public function type() : BelongsTo
    {
        return $this->belongsTo(DataType::class);
    }
}
