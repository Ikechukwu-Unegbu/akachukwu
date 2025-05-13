<?php

namespace App\Http\Controllers\SystemUser\ActivityLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivitLogController extends Controller
{
    public function index()
    {
        return view('system-user.activity.index');
    }
}
