<?php

namespace App\Http\Controllers\SystemUser\ActivityLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityHeatMapController extends Controller
{
    public function index()
    {
        return view('system-user.activity.map');
    }
}
