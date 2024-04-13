<?php

namespace App\Http\Controllers\V1\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class RegisterUserController extends Controller
{
    public function register(Request $request)
    {
        // var_dump($request->all());die;
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone'=>['required'],
            'terms_and_conditions'=>['nullable']
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'  =>  'user'
        ]);

        return response()->json([
            'message'=>'Account Created.',
            'status'=>'success',
            'user'=>$user
        ], 200);
    }

    public function destroy($userId)
    {

    }
}
