@php
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

// Simulate Savings Accounts Data
$savingsData = collect();
foreach (range(1, 50) as $i) {
    $plan = ['Fixed', 'Flexible'][rand(0, 1)];
    $status = ['Active', 'Paused', 'Incomplete', 'Matured'][rand(0, 3)];
    $savingsData->push([
        'id' => $i,
        'user_id' => "#00$i",
        'name' => "User $i",
        'plan' => $plan,
        'amount' => rand(10000, 500000),
        'start' => now()->subDays($i * 2),
        'end' => now()->addDays($i * 3),
        'status' => $status,
    ]);
}

// Simulate Transactions Data
$transactionData = collect();
foreach (range(1, 30) as $i) {
    $type = ['Deposit', 'Withdrawal'][rand(0, 1)];
    $status = ['Pending', 'Successful', 'Failed'][rand(0, 2)];
    $transactionData->push([
        'user' => "User $i",
        'type' => $type,
        'amount' => rand(5000, 100000),
        'status' => $status,
        'source' => ['Card', 'Bank Transfer', 'Wallet'][rand(0, 2)],
        'scheduled' => rand(0, 1) ? 'Yes' : 'No',
    ]);
}

// Paginate both datasets (change perPage if needed)
$perPage = 10;
$page = request()->get('page', 1);

$savingsPaginated = new LengthAwarePaginator(
    $savingsData->forPage($page, $perPage),
    $savingsData->count(),
    $perPage,
    $page,
    ['path' => request()->url(), 'query' => request()->query()]
);

$transactionsPaginated = new LengthAwarePaginator(
    $transactionData->forPage($page, $perPage),
    $transactionData->count(),
    $perPage,
    $page,
    ['path' => request()->url(), 'query' => request()->query()]
);
@endphp

@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <x-admin.page-title title="Savings Accounts">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Savings Accounts" status="true" />
    </x-admin.page-title>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-2" method="GET">
                <div class="col-md-3 col-6">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search user or plan">
                </div>
                <div class="col-md-2 col-6">
                    <select class="form-select" name="plan">
                        <option value="">All Plans</option>
                        <option value="Fixed" {{ request('plan') === 'Fixed' ? 'selected' : '' }}>Fixed</option>
                        <option value="Flexible" {{ request('plan') === 'Flexible' ? 'selected' : '' }}>Flexible</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        @foreach(['Active', 'Paused', 'Incomplete', 'Matured'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <input type="date" name="start" value="{{ request('start') }}" class="form-control">
                </div>
                <div class="col-md-2 col-6">
                    <input type="date" name="end" value="{{ request('end') }}" class="form-control">
                </div>
                <div class="col-md-1 col-6">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Savings Accounts --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">User Savings Accounts</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Plan</th>
                        <th>Amount Saved</th>
                        <th>Start - End</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($savingsPaginated as $row)
                        <tr>
                            <td>{{ $row['user_id'] }}</td>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['plan'] }}</td>
                            <td>₦{{ number_format($row['amount']) }}</td>
                            <td>{{ $row['start']->format('d M Y') }} - {{ $row['end']->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ match($row['status']) {
                                    'Active' => 'success',
                                    'Paused' => 'warning',
                                    'Incomplete' => 'secondary',
                                    'Matured' => 'primary'
                                } }}">{{ $row['status'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $savingsPaginated->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- Transactions --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Recent Savings Transactions</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Source</th>
                        <th>Scheduled?</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactionsPaginated as $tx)
                        <tr>
                            <td>{{ $tx['user'] }}</td>
                            <td>{{ $tx['type'] }}</td>
                            <td>₦{{ number_format($tx['amount']) }}</td>
                            <td>
                                <span class="badge bg-{{ match($tx['status']) {
                                    'Successful' => 'success',
                                    'Pending' => 'warning',
                                    'Failed' => 'danger'
                                } }}">{{ $tx['status'] }}</span>
                            </td>
                            <td>{{ $tx['source'] }}</td>
                            <td>{{ $tx['scheduled'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No transactions</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $transactionsPaginated->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('title')
View Savings Accounts
@endpush
