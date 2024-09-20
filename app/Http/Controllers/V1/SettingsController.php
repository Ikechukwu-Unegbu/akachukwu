<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('pages.settings.index');
    }

    public function referral()
    {
        return view('pages.settings.referral');
    }

    public function support()
    {
        return view('pages.settings.support');
    }

    public function credentials()
    {
        return view('pages.settings.credentials');
    }
}
