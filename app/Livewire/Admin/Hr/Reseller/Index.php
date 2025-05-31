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
    public $param; // For query parameters
    public $startDate;
    public $endDate;

    protected $queryString = [
        'search' => ['except' => ''],
        'param' => ['except' => ''],
        'perPage' => ['except' => 50],
        'endDate' => ['except' => ''],
        'startDate' => ['except' => ''],
        'filter' => ['except' => 'request'],
    ];

    public function mount()
    {
        $this->filter = 'request';
        $this->authorize('view users');
    }

    public function render()
    {
        // Start query builder
        if ($this->filter === 'request') {
            $upgradeRequests = UpgradeRequest::pending()->pluck('user_id')->toArray();
            $query = User::whereIn('id', $upgradeRequests);
        } elseif ($this->filter === 'reseller') {
            $query = User::where('user_level', 'reseller');
        } else {
            $query = User::query();
        }

        // Apply date filter if set
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [
                date('Y-m-d 00:00:00', strtotime($this->startDate)),
                date('Y-m-d 23:59:59', strtotime($this->endDate))
            ]);
        }

        // Apply search if set (assuming you have a search scope)
        if ($this->search) {
            $query->search($this->search);
        }

        $resellers = $query
            ->withTrashed()
            ->orderBy('account_balance', 'desc')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.hr.reseller.index', [
            'resellers' => $resellers
        ]);
    }
}
