<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'transactions'  => auth()->user()->checkUserTransactionHistories(10, auth()->id()),
        ]);
    }
}
