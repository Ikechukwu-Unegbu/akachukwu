<?php

namespace App\Http\Controllers\V1\API\Auth;

use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use App\Helpers\GeneralHelpers;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Notifications\WelcomeEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserDeviceRepository;
use Illuminate\Support\Facades\Notification;
use App\Services\OneSignalNotificationService;
use Illuminate\Validation\ValidationException;
use App\Notifications\RegistrationOSNotification;


class RegisterUserController extends Controller
{
    public $userDeviceRepository;
    public $notificationService;

    public function __construct(public OTPService $otpService, UserDeviceRepository $userDeviceRepository)
    {
        $this->userDeviceRepository = $userDeviceRepository;
        $this->notificationService = new OneSignalNotificationService();
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

            GeneralHelpers::checkReferrer($request, $user);

            Notification::sendNow($user, new WelcomeEmail($otp, $user));

            if ($request->os_player_id) {
                $this->userDeviceRepository->updateOrCreate(['os_player_id' => $request->os_player_id]);
            }

            $this->notificationService->sendToUser($user, "Welcome to Vastel {$user->username}!", 'Your Vastel Account is ready. Thank you for using Vastel!');

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
