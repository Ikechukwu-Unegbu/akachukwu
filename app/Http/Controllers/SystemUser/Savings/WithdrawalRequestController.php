<?php

namespace App\Http\Controllers\SystemUser\Savings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawalRequestController extends Controller
{
    public function index()
    {
        return view('system-user.savings.withdrawal.index');
    }
}
