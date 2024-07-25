<?php

namespace App\Http\Controllers\V1\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

       
        $status = Password::sendResetLink(
            $request->only('email')
        );
        
        
        // return $status === Password::RESET_LINK_SENT
        //             ? response()->json([
        //                 'message' => __($status),
        //                 'status'=>'success'
        //             ], 200)
        //             : response()->json([
        //                 'error' => __($status),
        //                 'status'=>'failed'
        //             ], 500);

        if ($status === Password::RESET_LINK_SENT) {
            Session::flash('done', "Password reset link sent to your email.");
            return redirect()->to(url()->previous() . '?success=1');

        } else {

            Session::flash('error', "Unable to send password reset link.");
            return redirect()->back();
        }

        
    }


}
