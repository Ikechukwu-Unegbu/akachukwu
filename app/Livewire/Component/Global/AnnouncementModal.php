<?php

namespace App\Livewire\Component\Global;

use App\Models\Announcement;
use Livewire\Component;

class AnnouncementModal extends Component
{
    public function render()
    {
        $announcements = Announcement::where('is_active', true)->get();
        return view('livewire.component.global.announcement-modal', [
            'announcements' => $announcements,
            'hasAnnouncements' => $announcements->isNotEmpty(),
        ]);
    }
    
}
