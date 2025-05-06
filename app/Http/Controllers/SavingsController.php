<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SavingsController extends Controller
{
    /**
     * Display the savings page based on the request type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->query('type');
        
        // If a specific savings type is requested
        if ($type) {
            switch ($type) {
                case 'vassave':
                    return view('savings.vassave');
                case 'autosave':
                    return view('savings.autosave');
                case 'vasfixed':
                    return view('savings.vasfixed');
                default:
                    // Fallback to main savings page if unknown type
                    return view('savings.index');
            }
        }
        
        // Default: show main savings overview
        return view('savings.index');
    }
}