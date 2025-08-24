<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Media;
use App\Services\Uploads\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:view media')->only('index');
        $this->middleware('can:create media')->only(['store', 'create']);
        $this->middleware('can:edit media')->only('update');
        $this->middleware('can:delete media')->only('destroy');
    }

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
    public function store(Request $request, ImageService $imageService)
    {
        $model = new Media();
        $model->name = $request->name;
        $model->type = $request->media_type;
        $model->user_id = Auth::user()->id;
        $model->useage = $request->usage;
        $model->save();

      
        $imageService->fileUploader($request, '/media', $model, 'path');
        return redirect()->back()->with('success', 'Blog post created successfully.');
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
        $mediaItem = Media::find($id);
        if($mediaItem->path){
            Storage::disk('do')->delete($mediaItem->path);
        }
        $mediaItem->delete();
        return redirect()->back()->with('success', 'Successfully deleted.');
    }
}
