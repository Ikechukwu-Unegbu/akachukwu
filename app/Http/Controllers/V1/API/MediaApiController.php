<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\Media;

class MediaApiController extends Controller
{
 
    public function index()
    {
        $mediaFiles = Media::where('useage', Media::USAGES['MOBILE_APP_BANNER'])->get();
        return response()->json($mediaFiles);
    }
}
