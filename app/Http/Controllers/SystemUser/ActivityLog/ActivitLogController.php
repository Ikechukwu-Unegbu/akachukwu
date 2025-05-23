<?php

namespace App\Http\Controllers\SystemUser\ActivityLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Helpers\ActivityConstants;
use ReflectionClass;

class ActivitLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        // Filter by activity type
        if ($request->filled('activity_type')) {
            $query->where('type', $request->activity_type);
        }

        // Filter by username or email
        if ($request->filled('username')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->username . '%')
                  ->orWhere('email', 'like', '%' . $request->username . '%');
            });
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $activityLogs = $query->latest()->paginate(30)->withQueryString();

        $constants = (new ReflectionClass(ActivityConstants::class))->getConstants();

        return view('system-user.activity.index', [
            'activities' => $activityLogs,
            'constants' => $constants
        ]);
    }
}
