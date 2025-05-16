<?php

namespace App\Livewire\Admin\Hr\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use App\Services\Admin\Activity\ActivityLogService;
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
        $this->authorize('view users');
        ActivityLogService::log([
            'activity'=>"View",
            'description'=>'Viewing User Index',
            'type'=>'Users',
        ]);
    }

    public function render()
    {
        $query = User::query();


        if ($this->search) {
            $query->search($this->search);
      
            ActivityLogService::log([
                'activity'=>"Search",
                'description'=> Auth::user()->name.' searched. '.$this->search,
                'type'=>'Users',
            ]);
      
        }

        if ($this->param === 'blocked') {
            $query->where('blocked_by_admin', true); // Assuming "status" is the column for blocked users
        } elseif ($this->param === 'negative-balance') {
            $query->where('account_balance', '<', 0);
        }

        if ($this->startDate && $this->endDate) {
            // dd('hell');
            $query->whereBetween('created_at', [
                date('Y-m-d 00:00:00', strtotime($this->startDate)),
                date('Y-m-d 23:59:59', strtotime($this->endDate))
            ]);
        }

        $users = $query
            ->withTrashed()
            ->whereRole('user')
            ->orderBy('account_balance', 'desc')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.hr.user.index', compact('users'));
    }
}
