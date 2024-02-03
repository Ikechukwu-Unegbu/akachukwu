<?php
namespace App\Models;

use App\Models\Data\DataVendor;

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
}