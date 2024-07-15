<?php

namespace App\Http\Controllers\V1\Education;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResultCheckerController extends Controller
{
    public function index()
    {
        return view('pages.education.result.index');
    }
}
