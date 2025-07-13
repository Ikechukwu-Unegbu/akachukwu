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
    public $param; // For query parameters
    public $startDate;
    public $endDate;

    protected $queryString = [
        'search' => ['except' => ''],
        'param' => ['except' => ''],
        'perPage' => ['except' => 50],
        'endDate' => ['except' => ''],
        'startDate' => ['except' => ''],
    ];

    public function mount()
    {
        $this->authorize('view administrators');
    }

    public function render()
    {
        $query = User::query()->whereRole('admin');

        // Apply search if set (assuming you have a search scope)
        if ($this->search) {
            $query->search($this->search);
        }

        // Apply date filter if set
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                date('Y-m-d 00:00:00', strtotime($this->startDate)),
                date('Y-m-d 23:59:59', strtotime($this->endDate))
            ]);
        }

        $users = $query->latest()->paginate($this->perPage);

        return view('livewire.admin.hr.administrator.index', [
            'users' => $users
        ]);
    }
}
