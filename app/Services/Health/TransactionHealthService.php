<?php 
namespace App\Services\Health;

use App\Models\Data\DataTransaction as DataDataTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\DataTransaction;

class TransactionHealthService
{
    public function getLastFailedTransactions()
    {
        $airtimeFailed = AirtimeTransaction::where('vendor_status', '!=', 'successful')
            ->latest()
            ->limit(3)
            ->get();

        $dataFailed = DataDataTransaction::where('vendor_status', '!=', 'successful')
            ->latest()
            ->limit(3)
            ->get();

        return [
            'airtime' => $airtimeFailed,
            'data' => $dataFailed
        ];
    }
}
