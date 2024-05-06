<?php

namespace App\Livewire\Component\Admin;

use App\Models\SiteSetting;
use Livewire\Component;

class SiteSettings extends Component
{

    public $siteSettings;
    public function mount()
    {
        $this->authorize('view users');
        $this->siteSettings = SiteSetting::first();
    }
    public function render()
    {
        return view('livewire.component.admin.site-settings',  [
            'setting' => $this->siteSettings,
        ]);
    }
}
