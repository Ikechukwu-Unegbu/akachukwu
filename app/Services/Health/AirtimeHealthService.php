<?php 
namespace App\Services\Health;

use App\Models\Utility\AirtimeTransaction;
use Carbon\Carbon;

class AirtimeHealthService {

    public function successRate($hours = 1)
    {
 
        $startTime = Carbon::now()->subHours($hours);
        $endTime = Carbon::now();

        $stats = AirtimeTransaction::whereBetween('created_at', [$startTime, $endTime])
            ->selectRaw("COUNT(*) as total, SUM(CASE WHEN vendor_response = 'successful' THEN 1 ELSE 0 END) as successful")
            ->first();

        return $stats->total > 0 
            ? round(($stats->successful / $stats->total) * 100, 2) 
            : 0;

    }
}