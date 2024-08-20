<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function impersonate(User $user)
    {
        Auth::user()->impersonate($user);
        return redirect()->route('dashboard');
    }

    public function stopImpersonating()
    {
        $user = Auth::user()->stopImpersonating();
        // dd($user);
        return redirect()->route('admin.dashboard');
    }
}
