<?php

namespace App\Livewire\Admin\Role;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public function mount()
    {
        $this->authorize('view role');
    }
    
    public function render()
    {
        return view('livewire.admin.role.index', [
            'roles' =>  Role::with(['users', 'permissions'])->when($this->search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })->paginate($this->perPage),
            'permissions_count' => Permission::count()
        ]);
    }
}
