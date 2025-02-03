<?php

namespace App\Livewire\Admin\Announcement;

use Livewire\Component;
use App\Models\Announcement;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    public $announcement;
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
    #[Validate('nullable|url')]
    public $link;

    public function mount(Announcement $announcement)
    {
        $this->authorize('edit announcement');
        $this->announcement = $announcement;
        $this->title = $this->announcement->title;
        $this->message = $this->announcement->message;
        $this->type = $this->announcement->type;
        $this->start_at = $this->announcement->start_at;
        $this->end_at = $this->announcement->end_at;
        $this->link = $this->announcement->link;
        $this->is_active = $this->announcement->is_active ? true : false;
    }

    public function update()
    {
        $this->announcement->update($this->validate());
        $this->dispatch('success-toastr', ['message' => 'Announcement updated successfully']);
        session()->flash('status', 'Announcement updated successfully.');
        return $this->redirectRoute('admin.announcement.index');
    }

    public function render()
    {
        return view('livewire.admin.announcement.edit');
    }
}
