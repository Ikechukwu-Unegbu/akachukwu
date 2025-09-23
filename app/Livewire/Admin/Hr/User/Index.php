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
    public array $selectedUsers = [];
    public string $bulkAction = '';



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
            $query->where('blocked_by_admin', true);
        } elseif ($this->param === 'negative-balance') {
            $query->where('account_balance', '<', 0);
        } elseif ($this->param === 'flagged') {
            $query->where('is_flagged', true);
        } elseif ($this->param === 'post-no-debit') {
            $query->where('post_no_debit', true);
        } elseif ($this->param === 'balance-high') {
            $query->where('account_balance', '>', 10000); // Users with balance > ₦10,000
        } elseif ($this->param === 'balance-low') {
            $query->where('account_balance', '<', 1000); // Users with balance < ₦1,000
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                date('Y-m-d 00:00:00', strtotime($this->startDate)),
                date('Y-m-d 23:59:59', strtotime($this->endDate))
            ]);
        }

        $users = $query
            ->withTrashed()
            ->with(['flaggedByAdmin', 'postNoDebitByAdmin', 'blacklistedByAdmin'])
            ->whereRole('user')
            ->orderBy('account_balance', 'desc')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.hr.user.index', compact('users'));
    }

    public function confirmBulkAction($action)
{
    $this->bulkAction = $action;

    // Trigger JS to show modal
    $this->dispatchBrowserEvent('show-bulk-confirm-modal');
}

public function performBulkAction()
{
    if ($this->bulkAction === 'blacklist') {
        User::whereIn('id', $this->selectedUsers)
            ->update([
                'is_blacklisted' => true,
                'blacklisted_by' => auth()->id()
            ]);
    }

    if ($this->bulkAction === 'block') {
        User::whereIn('id', $this->selectedUsers)
            ->update([
                'post_no_debit' => true,
                'post_no_debit_by' => auth()->id()
            ]);
    }

    $this->selectedUsers = [];
    $this->bulkAction = '';

    $this->dispatchBrowserEvent('hide-bulk-confirm-modal');
    session()->flash('success', 'Bulk action applied successfully.');
}
}
