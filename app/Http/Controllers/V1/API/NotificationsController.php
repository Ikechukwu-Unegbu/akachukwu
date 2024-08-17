<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $notification = Announcement::where('is_active', true)->paginate(10);
        return response()->json([
            'status'=>'success', 
            'message'=>'notification fetched', 
            'data'=>$notification
        ]);
    }
}
