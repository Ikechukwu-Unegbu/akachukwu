<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function index()
    {
        $blacklists = Blacklist::paginate(40);
        return view('system-user.blacklist.index')->with('blacklists', $blacklists);
    }

    public function store(Request $request)
    {

    }

    public function destroy()
    {

    }
}
