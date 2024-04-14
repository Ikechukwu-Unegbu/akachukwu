<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\V1\User\UserProfileService;


class UserProfileController extends Controller
{

    protected $userApiService;
        
    public function __construct(UserProfileService $userApiService)
    {
        $this->userApiService = $userApiService;
    }

    public function show($username)
    {
        return response()->json($this->userApiService->getUser($username));
    }
}
