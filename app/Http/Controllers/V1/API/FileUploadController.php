<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\FileUpdateRequest;
use App\Http\Requests\V1\API\FileUploadRequest;
use App\Models\User;
use App\Services\Uploads\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
