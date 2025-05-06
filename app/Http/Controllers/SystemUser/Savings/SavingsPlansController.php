<?php

namespace App\Http\Controllers\SystemUser\Savings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SavingsPlansController extends Controller
{
    public function index()
    {
        return view('system-user.savings.package.index');
    }
}
