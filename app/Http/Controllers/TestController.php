<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\WelcomeEmail;
use Illuminate\Support\Facades\Notification;


class TestController extends Controller
{
    public function testmail(){
        $user = User::where('email', 'mr.ikunegbu@gmail.com')->first();
        if ($user) {
            Notification::send($user, new WelcomeEmail());
            return response()->json(['message' => 'Email sent successfully.']);
        }

        return response()->json(['message' => 'User not found.'], 404);
    }
    
}
