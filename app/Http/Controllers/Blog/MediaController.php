<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
     /**
     * Display a listing of the media resources.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mediaFiles = Media::paginate(20);
        return view('system-user.blog.media.index')->with('mediaFiles', $mediaFiles);
    }

    /**
     * Store newly uploaded media files in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Handle the upload and storage of media files
    }

    /**
     * Display the specified media resource.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Show the details of a specific media file
    }

    /**
     * Update the specified media resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Update the media file details
    }

    /**
     * Remove the specified media resource from storage.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete the media file from storage and database
    }
}
