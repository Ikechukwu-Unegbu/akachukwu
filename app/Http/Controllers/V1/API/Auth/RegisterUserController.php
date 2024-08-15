<?php

namespace App\Http\Controllers\V1\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\WelcomeEmail;
use App\Services\OTPService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class RegisterUserController extends Controller
{

    public function __construct(public OTPService $otpService)
    {
        
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone'=>['required'],
            'terms_and_conditions'=>['nullable']
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

   
        return DB::transaction(function()use($request){
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role'  =>  'user'
            ]);
            $otp = $this->otpService->generateOTP($user);

            Notification::sendNow($user, new WelcomeEmail($otp, $user));

            return response()->json([
                'message'=>'Account Created.',
                'status'=>'success',
                'user'=>$user
            ], 200);
        });
    }

    public function destroy($userId)
    {

    }
}
