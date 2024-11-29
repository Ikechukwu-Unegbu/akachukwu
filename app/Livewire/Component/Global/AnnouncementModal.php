<?php

namespace App\Livewire\Component\Global;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementModal extends Component
{
    public function render()
    {
        $announcements = Announcement::where('is_active', true)
                        ->where('start_at', '<=', Carbon::now())
                        ->where('end_at', '>=', Carbon::now())     
                        ->get();

        $user = Auth::user();
        $hasCompletedKyc = $user && ($user->bvn || $user->nin); 

        return view('livewire.component.global.announcement-modal', [
            'announcements' => $announcements,
            'hasAnnouncements' => $announcements->isNotEmpty(),
            'hasCompletedKyc' => $hasCompletedKyc, 
        ]);
    }
    
}
