<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $refs = $user->getReferredUsersWithEarnings();
        return ApiHelper::sendResponse($refs, 'Refs successfully fetched');
    }
}
