<?php 
namespace App\Services\Health;

use App\Models\Data\DataTransaction;
use Carbon\Carbon;

class DataHealthService {

    public function successRate($hours = 1)
    {
 
        $startTime = Carbon::now()->subHours($hours);
        $endTime = Carbon::now();

        $stats = DataTransaction::swhereBetween('created_at', [$startTime, $endTime])
            ->selectRaw("COUNT(*) as total, SUM(CASE WHEN status = 'successful' THEN 1 ELSE 0 END) as successful")
            ->first();

        return $stats->total > 0 
            ? round(($stats->successful / $stats->total) * 100, 2) 
            : 0;

    }
}