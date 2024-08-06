<?php 
namespace App\Services\Uploads;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageService{

    public $path;
    public function __construct()
    {
        $environment = env('APP_ENV') === 'production' ? 'production' : 'staging';
        $this->path = $environment;
    }
  
    public function storeAvatar($request)
    {
        
        $imageUrl = Storage::disk('do')->put($this->path, $request->file('image'), 'public');
        $user = User::find(Auth::user()->id);
        $user->image = env('DO_CDN').'/'.$imageUrl;
        $user->save();
        
        return env('DO_CDN').'/'.$imageUrl;
    }


    public function updateAvatar($request)
    {
        $user = User::find(Auth::user()->id);
        $oldImage = $user->image;

        $imageUrl = Storage::disk('do')->put($this->path, $request->file('image'), 'public');
        $newImageUrl = env('DO_CDN').'/'.$imageUrl;

        if ($oldImage) {
            $oldImagePath = str_replace(env('DO_CDN').'/', '', $oldImage);
            Storage::disk('do')->delete($oldImagePath);
        }

        $user->image = $newImageUrl;
        $user->save();

        return $newImageUrl;
    }

    public function storeNepaBill()
    {

    }

}