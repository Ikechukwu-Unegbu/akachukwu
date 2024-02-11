<?php

namespace App\Livewire\Admin\Hr\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;
    
    public function mount()
    {
        $this->authorize('view users');
    }

    public function render()
    {
        return view('livewire.admin.hr.user.index', [
            'users' =>    User::search($this->search)->whereRole('user')->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
