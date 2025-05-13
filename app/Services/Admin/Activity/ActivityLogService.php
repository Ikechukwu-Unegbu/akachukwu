<?php 
namespace App\Services\Admin\Activity;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogService{

    public static function log(array $data): ActivityLog
    {
        return ActivityLog::create([
            'actor_id' => $data['actor_id'] ?? Auth::id(),
            'resource_owner_id'=> $data['resource_owner']??null,
            'activity' => $data['activity'],
            'resource'=>$data['resource']??null,
            
            'description' => $data['description'] ?? null,
            'type' => $data['type'] ?? null,
            'raw_request' => $data['raw_request'] ?? null,
            'raw_response' => $data['raw_response'] ?? null,
            'ip_address' => $data['ip_address'] ?? Request::ip(),
            'user_agent' => $data['user_agent'] ?? Request::userAgent(),
            'location' => $data['location'] ?? null,
            'balance_before' => $data['balance_before'] ?? null,
            'balance_after' => $data['balance_after'] ?? null,
            'tags' => $data['tags'] ?? [],
            'ref_id' => $data['ref_id'] ?? null,
        ]);
    }

}
