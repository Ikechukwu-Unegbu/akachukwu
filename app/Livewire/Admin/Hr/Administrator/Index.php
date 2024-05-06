<?php

namespace App\Livewire\Admin\Hr\Administrator;

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
        $this->authorize('view administrators');
         
    }
    
    public function render()
    {
        return view('livewire.admin.hr.administrator.index', [
            'users' =>    User::search($this->search)->whereRole('admin')->latest()->paginate($this->perPage)
        ]);
    }
}
