<?php

namespace App\Services;

use App\Models\Compliance;

class ComplianceService
{
    public static function storePayload($payload, $bvn = null, $nin = null)
    {
        return Compliance::payload($payload, $bvn, $nin);
    }
}