<?php

namespace App\Livewire\Admin\Announcement;

use App\Helpers\GeneralHelpers;
use App\Models\Announcement;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate('required|string')]
    public $title;
    #[Validate('required|string|max:1500')]
    public $message;
    #[Validate('required|string')]
    public $type = 'info';
    #[Validate('nullable|date')]
    public $start_at;
    #[Validate('nullable|date')]
    public $end_at;
    #[Validate('nullable')]
    public $is_active;
    public $uuid;
    #[Validate('nullable|url')]
    public $link;


    public function save()
    {
        Announcement::create($this->validate());
        $this->dispatch('success-toastr', ['message' => 'Announcement added successfully']);
        session()->flash('status', 'Announcement added successfully.');
 
        return $this->redirectRoute('admin.announcement.index');
    }

    public function mount()
    {
        $this->authorize('create announcement');
        $this->uuid = GeneralHelpers::generateUniqueUuid('announcements');
    }

    public function render()
    {
        return view('livewire.admin.announcement.create');
    }
}
