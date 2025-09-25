<?php

namespace App\Services\Data;

use App\Models\Vendor;
use App\Helpers\ApiHelper;
use App\Traits\HandlesPostNoDebit;
use App\Traits\ResolvesVendorService;
use App\Services\Vendor\VendorServiceFactory;

class DataService
{
    use ResolvesVendorService, HandlesPostNoDebit;

    public static function create($vendorId, $networkId, $typeId, $dataId, $mobileNumber, $isScheduled = false, $scheduledPayload = [], $isInitialRun = false, $hasTransaction = null, $wallet='base_wallet')
    {
        if ( self::ensurePostNoDebitIsAllowed()) {
            return ApiHelper::sendError([], 'Your account is restricted from performing debit operations.', 403);
        }

        $vendorService = (new self)->resolveServiceClass('data');
        return $vendorService::data($networkId, $typeId, $dataId, $mobileNumber, $isScheduled, $scheduledPayload, $isInitialRun, $hasTransaction, $wallet);
    }
}
