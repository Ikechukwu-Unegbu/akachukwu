<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\FileUpdateRequest;
use App\Http\Requests\V1\API\FileUploadRequest;
use App\Services\Uploads\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function __construct(public ImageService $imageService)
    {
        
    }
    public function store(FileUploadRequest $request)
    {
       
        $fileUrl = $this->imageService->storeAvatar($request);
        return response()->json([
            "file"=>$fileUrl
        ]);
    }

    public function update(FileUpdateRequest $request)
    {
        $fileUrl = $this->imageService->updateAvatar($request);
        return response()->json([
            'file'=>$fileUrl
        ]);
    }


    
}
