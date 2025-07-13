<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;
    protected $guarded = [

    ];

    protected $casts = [
        // 'bank_config' => 'json'
    ];

    public function updateAirtimeSale()
    {
        $this->airtime_sales = !$this->airtime_sales;
        $this->save();
    }
}
