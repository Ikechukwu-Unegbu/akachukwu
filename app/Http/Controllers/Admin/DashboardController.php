<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $months = [];
        for ($m=1; $m<=12; $m++) $months[] = date('m', mktime(0,0,0,$m, 1, date('Y')));
        foreach ($months as $month) {
            $months[] = date('M', mktime(0,0,0,$month, 1, date('Y'))); 
            $registeredUser[] = User::whereRole('user')->whereRaw('MONTH(created_at) = ?', $month)->count();
        
        }

        return view('pages.admin.dashboard', compact('months', 'registeredUser'));
    }
}
