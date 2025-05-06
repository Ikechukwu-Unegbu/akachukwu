<?php

namespace App\Http\Controllers\SystemUser\Savings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SavingsHomeController extends Controller
{
    public function dashboard()
    {
        return view('system-user.savings.home');
    }

    public function index()
    {
        return view('system-user.savings.index');
    }
}
