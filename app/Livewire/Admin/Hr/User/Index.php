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
    public $param; // For query parameters

    protected $queryString = [
        'search' => ['except' => ''],
        'param' => ['except' => ''],
        'perPage' => ['except' => 50],
    ];

    public function mount()
    {
        $this->authorize('view users');
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->search($this->search);
        }

        if ($this->param === 'blocked') {
            $query->where('blocked_by_admin', true); // Assuming "status" is the column for blocked users
        } elseif ($this->param === 'negative-balance') {
            $query->where('account_balance', '<', 0);
        }

        $users = $query
            ->whereRole('user')
            ->orderBy('account_balance', 'desc')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.hr.user.index', compact('users'));
    }
}
