<?php

namespace App\Livewire\Admin\Announcement;

use App\Helpers\GeneralHelpers;
use App\Models\Announcement;
use Livewire\Component;

class Create extends Component
{
    public $title;
    public $message;
    public $type = 'info';
    public $start_at;
    public $end_at;
    public $is_active;
    public $uuid;


    public function save()
    {
        Announcement::create(
            $this->only(['uuid','title', 'message', 'type', 'start_at', 'end_at'])
        );
 
        session()->flash('status', 'Post successfully updated.');
 
        return $this->redirect('/admin/announcement');
    }

    public function mount()
    {
        $this->uuid = GeneralHelpers::generateUniqueUuid('announcements');
    }

    public function render()
    {
        return view('livewire.admin.announcement.create');
    }
}
