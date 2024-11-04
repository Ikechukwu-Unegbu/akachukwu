<?php
namespace App\Models;

use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Models\Education\ResultChecker;
use App\Models\Utility\Cable;
use App\Models\Utility\Electricity;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends DataVendor
{
    protected $table = 'data_vendors';

    /**
     * Set all status values to false.
     *
     * @return void
     */
    public static function setAllStatusToFalse()
    {
        self::query()->update(['status' => false]);
    }

    public function result_checkers()
    {
        return $this->hasMany(ResultChecker::class, 'vendor_id');
    }

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

    public function cables()
    {
        return $this->hasMany(Cable::class, 'vendor_id');
    }

    public function electricity()
    {
        return $this->hasMany(Electricity::class, 'vendor_id');
    }

    public function balances()
    {
        return $this->hasMany(VendorBalance::class, 'vendor_id');
    }
}