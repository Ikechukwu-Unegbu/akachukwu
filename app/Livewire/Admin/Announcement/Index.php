<?php

namespace App\Livewire\Admin\Announcement;

use App\Models\Announcement;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10; // Number of items per page



    public function render()
    {
        // return view('livewire.admin.announcement.index');
        $announcements = Announcement::paginate($this->perPage);

        return view('livewire.admin.announcement.index', [
            'announcements' => $announcements,
        ]);
    }
}
