<?php

namespace App\Http\Controllers\V1\API\Auth;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ApiHelper::sendError($errors, '');
        }

        $user = User::find(Auth::user()->id);//Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return ApiHelper::sendError(['Current password is incorrect'], 'Password incorrect');
          
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return ApiHelper::sendResponse([], 'Password successfully updated');
    }
}
