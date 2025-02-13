<?php 
namespace App\Services\Health;

use App\Models\Utility\AirtimeTransaction;
use Carbon\Carbon;

class AirtimeHealthService {

    public function successRate($duration = '1 hour')
    {
        $now = Carbon::now();
    
        switch ($duration) {
            case 'one week':
                $startTime = $now->subWeek();
                break;
            case 'one month':
                $startTime = $now->subMonth();
                break;
            case 'three months':
                $startTime = $now->subMonths(3);
                break;
            case 'six months':
                $startTime = $now->subMonths(6);
                break;
            case 'one year':
                $startTime = $now->subYear();
                break;
            default:
                $startTime = $now->subHour();
                break;
        }
    
        $stats = AirtimeTransaction::whereBetween('created_at', [$startTime, Carbon::now()])
            ->selectRaw("COUNT(*) as total, SUM(CASE WHEN vendor_response = 'successful' THEN 1 ELSE 0 END) as successful")
            ->first();
    
        return $stats->total > 0
            ? round(($stats->successful / $stats->total) * 100, 2)
            : 0;
    }
    
}