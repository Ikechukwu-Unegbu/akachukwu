<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;

class SiteSettingsController extends Controller
{
    public function update(Request $request)
    {
        // var_dump($request->all());die;
        $validatedData = $request->validate([
            'site_title' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'nullable|email|max:255',
            'phone1' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'total_users' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
        ], [
            'stie_title.required' => 'The site title field is required.',
            'site_logo.image' => 'The site logo must be an image file.',
            'site_logo.mimes' => 'The site logo must be a file of type: jpeg, png, jpg, gif.',
            'site_logo.max' => 'The site logo may not be greater than 2048 kilobytes in size.',
            // Add custom messages for other fields and rules
        ]);

        $siteSettings =  SiteSetting::find(1);
        if(!$siteSettings){
            $siteSettings = new SiteSetting();
        }
        $siteSettings->stie_title = $validatedData['site_title'];
        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $siteSettings->site_logo = 'uploads/' . $fileName;
        }
        $siteSettings->email = $validatedData['email'];
        $siteSettings->phone1 = $validatedData['phone1'];
        $siteSettings->phone2 = $validatedData['phone2'];
        $siteSettings->total_users = $validatedData['total_users'];
        $siteSettings->name = $validatedData['name'];
        $siteSettings->twitter = $validatedData['twitter'];
        $siteSettings->facebook = $validatedData['facebook'];
        $siteSettings->instagram = $validatedData['instagram'];
        $siteSettings->linkedin = $validatedData['linkedin'];
        
        $siteSettings->save();

        return redirect()->back()->with('success', 'Site settings updated successfully!');
    }
}
