@extends('layouts.admin.app')
@section('content')
    <x-admin.page-title title="Wallet Funding">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Wallet Funding" />
        <x-admin.page-title-item subtitle="In-App" status="true" />
    </x-admin.page-title>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">Wallet Funding</h5>
            </div>
        </div>

          <!-- Filters Form -->
    <div>
        <div class="card">
            <div class="p-4 card-body">
                <form method="GET">
                    <div class="mb-4 row">
                        <div class="mb-3 col-12 col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="dateFrom" value="{{ $filters['dateFrom'] ?? '' }}" class="form-control">
                        </div>
                        <div class="mb-3 col-12 col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="dateTo" value="{{ $filters['dateTo'] ?? '' }}" class="form-control">
                        </div>
                        <div class="mb-3 col-12 col-md-3">
                            <label class="form-label">Amount From</label>
                            <input type="number" name="amountFrom" value="{{ $filters['amountFrom'] ?? '' }}" placeholder="Min amount" class="form-control">
                        </div>
                        <div class="mb-3 col-12 col-md-3">
                            <label class="form-label">Amount To</label>
                            <input type="number" name="amountTo" value="{{ $filters['amountTo'] ?? '' }}" placeholder="Max amount" class="form-control">
                        </div>
                        <div class="mb-3 col-12 col-md-3">
                            <label class="form-label">Gateway</label>
                            <select name="vendor" id="vendor" class="form-select">
                                <option value="">All Gateways</option>
                                @foreach (['flutterwave', 'paystack', 'payvessel', 'monnify', 'vastel', 'palmpay'] as $gateway)
                                    <option value="{{ $gateway }}" {{ ($filters['vendor'] ?? '') == $gateway ? 'selected' : '' }}>
                                        {{ ucfirst($gateway) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-12 col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" {{ ($filters['status'] ?? '') == $status ? 'selected' : '' }}>
                                        {{ Str::title($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="float-end">
                        <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                        <a href="{{ route('admin.transfer.bank') }}" class="btn btn-secondary">Reset Filters</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="hidden" name="perPage" value="{{ $filters['perPage'] ?? 50 }}">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control me-2" placeholder="Search...">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <form method="GET" class="d-inline">
                        <input type="hidden" name="search" value="{{ $filters['search'] ?? '' }}">
                        <select name="perPage" onchange="this.form.submit()" class="w-auto form-select d-inline-block">
                            @foreach([50, 100, 200] as $perPageOption)
                                <option value="{{ $perPageOption }}" {{ ($filters['perPage'] ?? 50) == $perPageOption ? 'selected' : '' }}>
                                    {{ $perPageOption }} per page
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" style="font-size: small;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Trx. ID</th>
                            <th>Username</th>
                            <th>Amount</th>
                            <th>Timestamp</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Gateway</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ ($transactions->firstItem() ?? 0) + $loop->index }}</td>
                                <td>{{ $transaction->transaction_id }}</td>
                                <td>{{ $transaction->username }}</td>
                                <td>₦{{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y h:i A') }}</td>
                                <td>
                                    <span class="badge
                                        {{ $transaction->vendor_status === 'successful' ? 'bg-success' : '' }}
                                        {{ $transaction->vendor_status === 'failed' ? 'bg-danger' : '' }}
                                        {{ $transaction->vendor_status === 'pending' ? 'bg-warning' : '' }}
                                        {{ $transaction->vendor_status === 'processing' ? 'bg-warning' : '' }}
                                        {{ $transaction->vendor_status === 'refunded' ? 'bg-warning' : '' }}">
                                        {{ ucfirst($transaction->vendor_status ?? $transaction->vendor_status) }}
                                    </span>
                                </td>
                                <td>{{ $transaction->type }}</td>
                                <td>{{ ucfirst($transaction->vendor) }}</td>
                                <td>
                                    <a href="{{ route('admin.wallet-funding.show', $transaction->transaction_id) }}" class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">No Records Found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="mt-3">
                {{ $transactions->appends($filters)->links() }}
            </div>
        </div>
    </div>

    </section>

    @push('title')
        Wallet Funding
    @endpush
@endsection
