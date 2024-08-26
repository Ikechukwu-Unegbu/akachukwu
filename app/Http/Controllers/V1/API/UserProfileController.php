<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\V1\User\UserProfileService;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{

    protected $userApiService;
        
    public function __construct(UserProfileService $userApiService)
    {
        $this->userApiService = $userApiService;
    }

    public function show($username)
    {
        $user = $this->userApiService->getUser($username);
        if(!$user){
            return ApiHelper::sendError([], 'No such user');
              
        }
        return ApiHelper::sendResponse($user, 'User exists'); 
        
    }

    public function update(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string',
            'phone' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ApiHelper::sendError($errors, '');
        }
    
        $updatedUser = $this->userApiService->updateUser($request->all());
    
        if ($updatedUser) {
            return ApiHelper::sendResponse($updatedUser, 'User updated');
        } else {
            return ApiHelper::sendError(['Failed to update'], 'Failed update attempt');
        }
    }
    
}
