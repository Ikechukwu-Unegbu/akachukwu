<?php

namespace App\Http\Controllers\V1\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request, $username)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = User::where('username', $username)->first();//Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(
                [
                    'message' => 'Current password is incorrect',
                    'status'=>'failed'
            ], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully',
            'status'=>'success'
        ], 200);
    }
}
