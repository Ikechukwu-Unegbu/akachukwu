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

    public function datanetwork()
    {
        return $this->belongsTo(DataNetwork::class, 'network_id');
    }

       /**
     * Calculate the discounted amount.
     *
     * @return float
     */
    public function getDiscountedAmount(): float
    {
        // Get the data discount percentage from the related DataNetwork
        $discount = $this->datanetwork->data_discount ?? 0;

        // Calculate the discounted amount
        return $this->amount - ($this->amount * ($discount / 100));
    }


}
