<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BlacklistController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
    
        
        $blacklists = Blacklist::when($query, function ($queryBuilder, $query) {
            return $queryBuilder->where('value', 'like', "%{$query}%");
                                
        })->paginate(40);
    
    
        return view('system-user.blacklist.index', compact('blacklists', 'query'));
    }
    

    public function store(Request $request)
    {
        Blacklist::create([
            'type'=>$request->type,
            'value'=>$request->value
        ]);
        Session::flash('success', 'Blacklist record created');
        return redirect()->back();
    }

    public function destroy($id)
    {   
        Blacklist::find($id)->delete();
        Session::flash('success', 'Successfully deleted.');
        return redirect()->back();
    }
}
