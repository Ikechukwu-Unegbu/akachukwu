<?php

namespace App\Livewire\Component\Global;

use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AnnouncementModal extends Component
{
    public function render()
    {
        $announcements = Announcement::where('is_active', true)->get();

        $user = Auth::user();
        $hasCompletedKyc = $user && ($user->bvn || $user->nin); 

        return view('livewire.component.global.announcement-modal', [
            'announcements' => $announcements,
            'hasAnnouncements' => $announcements->isNotEmpty(),
            'hasCompletedKyc' => $hasCompletedKyc, 
        ]);
    }
    
}
