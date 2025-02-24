<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Services\Uploads\ImageService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileSettingsController extends Controller
{

    public function __construct(public ImageService $imageService)
    {
        
    }

    public function edit()
    {
        // return view('pages.profile.edit');
        return view('pages.profile.profile');
    }


    public function editPin()
    {
        return view('pages.profile.pin');
    }

     /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = User::find(Auth::user()->id);
        
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            // 'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'phone' => 'required|string|max:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = User::find(Auth::user()->id);
        if ($request->hasFile('image-upload')) {
            if($user->image == null){
                $fileUrl = $this->imageService->storeAvatar($request);
            }else{
                $fileUrl = $this->imageService->updateAvatar($request);
            }  
        }

        $user->name = $request->input('firstname') . ' ' . $request->input('lastname');
     
        $user->phone = $request->input('phone');
        
        $user->save();

        return back()->with('success', 'Profile updated successfully.');

    }
}
