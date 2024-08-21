<?php

namespace App\Livewire\Admin\Settings;

use App\Models\SiteSetting;
use App\Services\Uploads\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class AppLogos extends Component
{
    use WithFileUploads;

    // #[Validate('image|max:1024')] // 1MB Max
    protected $rules = [
        'image' => 'required|image|max:1024',
        'type' => 'required|string',
    ];

    public $image;
    public $type;

    public function mount()
    {

    }

    public function saveImage()
    {
        $this->validate();

        $siteSetting = SiteSetting::first();
        if ($siteSetting) {
           
            $path = $this->image->store($this->getAppEnv().'/assets', [
                'disk' => 'do',
                'visibility' => 'public'
            ]);
            $imageUrl = Storage::disk('do')->url($path);
            
            $siteSetting->{$this->type} = env('DO_CDN').'/'.$imageUrl;
            $siteSetting->save();

            session()->flash('success', 'Image uploaded and saved successfully!');
        } else {
            session()->flash('error', 'SiteSetting record not found.');
        }

        $this->image = null;
        $this->type = null;
    }

      /**
     * Get the application environment status.
     *
     * @return string
     */
    public function getAppEnv(): string
    {
        return app()->environment('production') ? 'production' : 'staging';
    }

    public function render()
    {
        if (!Auth::user()->can('set site-setting')) {
            abort(403, 'Unauthorized');
        }

        $siteSetting = SiteSetting::find(1); // Or any other query to get the desired SiteSetting
    
        return view('livewire.admin.settings.app-logos', [
            'siteSetting' => $siteSetting
        ]);
    }

}
