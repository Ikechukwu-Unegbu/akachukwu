<?php

namespace App\Models\Data;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataTransaction extends Model
{
    protected $guarded = [];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function vendor() : BelongsTo
    {
        return $this->belongsTo(DataVendor::class);
    }

    public function data_plan() : BelongsTo
    {
        return $this->belongsTo(DataPlan::class, 'data_id', 'data_id');
    }

}
