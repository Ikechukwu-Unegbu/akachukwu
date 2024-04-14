<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{

    protected $userApiService;
        
    public function __construct()
    {
        
    }

    public function show($username)
    {
        return response()->json($this->userApiService->getUser($username));
    }
}
