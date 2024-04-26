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

    public function update(Request $request, $username)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username'=>'required|string',
            'phone'=>'required|string',
            'address'=>'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
        $updatedUser = $this->userApiService->updateUser($username, $request->all());
    
        if ($updatedUser) {
            return response()->json(['message' => 'User updated successfully', 'user' => $updatedUser]);
        } else {
            return response()->json(['message' => 'Failed to update user'], 500);
        }
    }
    
}
