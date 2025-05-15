@extends('layouts.admin.app')
@section('content')
    <x-admin.page-title title="Transfers">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transfer" />
        <x-admin.page-title-item subtitle="Bank Transfer" status="true" />
    </x-admin.page-title>

    <section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="p-1 m-1 card-title">Bank Transfer</h5>
        </div>
    </div>

    <!-- Filters Form -->
    <div>
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('admin.transfer.bank') }}" method="GET">
                    <div class="row mb-4">
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="dateFrom" value="{{ $filters['dateFrom'] ?? '' }}" class="form-control">
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="dateTo" value="{{ $filters['dateTo'] ?? '' }}" class="form-control">
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Amount From</label>
                            <input type="number" name="amountFrom" value="{{ $filters['amountFrom'] ?? '' }}" placeholder="Min amount" class="form-control">
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Amount To</label>
                            <input type="number" name="amountTo" value="{{ $filters['amountTo'] ?? '' }}" placeholder="Max amount" class="form-control">
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Bank</label>
                            <select name="bank" id="bank" class="form-select">
                                <option value="">All Banks</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->code }}" {{ ($filters['bank'] ?? '') == $bank->code ? 'selected' : '' }}>
                                        {{ $bank->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3 mb-3">
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
                    <form action="{{ route('admin.transfer.bank') }}" method="GET" class="d-flex">
                        <input type="hidden" name="perPage" value="{{ $filters['perPage'] ?? 50 }}">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control me-2" placeholder="Search...">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <form action="{{ route('admin.transfer.bank') }}" method="GET" class="d-inline">
                        <input type="hidden" name="search" value="{{ $filters['search'] ?? '' }}">
                        <select name="perPage" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
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
                            <th>Sender</th>
                            <th>Bank</th>
                            <th>Amount</th>
                            <th>Timestamp</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transfers as $transfer)
                            <tr>
                                <td>{{ ($transfers->currentPage() - 1) * $transfers->perPage() + $loop->iteration }}</td>
                                <td>{{ $transfer->reference_id }}</td>
                                <td>{{ $transfer->sender->name ?? 'N/A' }}</td>
                                <td>{{ $transfer->bank_name ?? 'N/A' }} <br> {{ $transfer->account_number }}</td>
                                <td>₦{{ number_format($transfer->amount, 2) }}</td>
                                <td>{{ $transfer->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <span class="badge
                                        {{ $transfer->transfer_status === 'successful' ? 'bg-success' : '' }}
                                        {{ $transfer->transfer_status === 'failed' ? 'bg-danger' : '' }}
                                        {{ $transfer->transfer_status === 'pending' ? 'bg-warning' : '' }}
                                        {{ $transfer->transfer_status === 'processing' ? 'bg-warning' : '' }}
                                        {{ $transfer->transfer_status === 'refunded' ? 'bg-warning' : '' }}">
                                        {{ ucfirst($transfer->transfer_status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.transfer.bank.details', $transfer->reference_id) }}" class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No Records Found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $transfers->appends($filters)->links() }}
            </div>
        </div>
    </div>
</section>


    @push('title')
        Transfer :: InApp
    @endpush
@endsection
