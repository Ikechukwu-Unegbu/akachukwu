<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function privacy_policy()
    {
        return view('pages.policy.privacy-policy');
    }

    public function refund_policy()
    {
        return view('pages.policy.refund-policy');
    }

    
}
