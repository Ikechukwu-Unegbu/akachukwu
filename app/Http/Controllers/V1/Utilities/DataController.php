<?php

namespace App\Http\Controllers\V1\Utilities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        return view('pages.utilities.data.index');
    }
    
}
