<?php

namespace App\Http\Controllers\V1\Utilities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AirtimeController extends Controller
{
    public function index()
    {
        return view('pages.utilities.airtime.index');
    }
}
