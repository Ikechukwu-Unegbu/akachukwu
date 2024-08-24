<?php

namespace App\Http\Controllers\V1\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Uploads\ImageService;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\V1\Api\FileUploadRequest;

class FileUploadController extends Controller
{
    public function __construct(public ImageService $imageService)
    {
        
    }
    public function store(FileUploadRequest $request)
    {
       
        $user = User::find(Auth::user()->id);
        if($user->image == null){
            $fileUrl = $this->imageService->storeAvatar($request);
        }else{
            $fileUrl = $this->imageService->updateAvatar($request);
        }
        
        return response()->json([
            "file"=>$fileUrl
        ]);
    }


    
}
