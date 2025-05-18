<?php

namespace App\Http\Controllers\SystemUser\ActivityLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;



class ActivitLogController extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::paginate(30);
        // foreach($activityLogs as $log)
        // {
        //     dump($log->getModelDifferences());
        // }
        // die;
        return view('system-user.activity.index')->with('activities', $activityLogs);
    }
}
