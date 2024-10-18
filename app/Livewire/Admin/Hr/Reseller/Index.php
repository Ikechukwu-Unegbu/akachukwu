<?php

namespace App\Livewire\Admin\Hr\Reseller;

use App\Models\User;
use App\Models\Utility\UpgradeRequest;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;
    public $filter;

    public function mount()
    {
        $this->filter = 'request';
        $this->authorize('view users');
    }
    
    public function render()
    {        
        if ($this->filter === 'request') {
            $upgradeRequests = UpgradeRequest::pending()->pluck('user_id')->toArray();
            $users = User::whereIn('id', $upgradeRequests);
        }

        if ($this->filter === 'reseller') {
            $users = User::where('user_level', 'reseller');
        }

        return view('livewire.admin.hr.reseller.index', [
            'resellers' =>    $users->search($this->search)->latest()->paginate($this->perPage)
        ]);
    }
}
