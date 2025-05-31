@extends('layouts.admin.app')
@section('content')
    <x-admin.page-title title="Transfers">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transfer" />
        <x-admin.page-title-item subtitle="In-App" status="true" />
    </x-admin.page-title>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">In-App Transfer</h5>
            </div>
        </div>

        <!-- Filters Form -->
        <div>
            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('admin.transfer.in-app') }}" method="GET">
                        <div class="row mb-4">
                            <div class="col-12 col-md-3">
                                <label class="form-label">From Date</label>
                                <input type="date" name="dateFrom" value="{{ request('dateFrom') }}" class="form-control">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">To Date</label>
                                <input type="date" name="dateTo" value="{{ request('dateTo') }}" class="form-control">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Amount From</label>
                                <input type="number" name="amountFrom" value="{{ request('amountFrom') }}"
                                    placeholder="Min amount" class="form-control">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Amount To</label>
                                <input type="number" name="amountTo" value="{{ request('amountTo') }}"
                                    placeholder="Max amount" class="form-control">
                            </div>
                        </div>
                        <div class="float-end">
                            <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                            <a href="{{ route('admin.transfer.in-app') }}" class="btn btn-secondary">Reset Filters</a>
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
                        <form action="{{ route('admin.transfer.in-app') }}" method="GET" class="d-flex">
                            <input type="hidden" name="perPage" value="{{ request('perPage', 50) }}">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2"
                                placeholder="Search...">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <form action="{{ route('admin.transfer.in-app') }}" method="GET" class="d-inline">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="perPage" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
                                @foreach([50, 100, 200] as $perPage)
                                    <option value="{{ $perPage }}" {{ request('perPage', 50) == $perPage ? 'selected' : '' }}>
                                        {{ $perPage }} per page</option>
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
                                <th>Recipient</th>
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
                                    <td>{{ $transfer->sender->username ?? 'N/A' }}</td>
                                    <td>{{ $transfer->receiver->username ?? 'N/A' }}</td>
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
                                        <a href="{{ route('admin.transfer.in-app.details', $transfer->reference_id) }}"
                                            class="btn btn-primary btn-sm">View</a>
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
                    {{ $transfers->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </section>

    @push('title')
        Transfer :: InApp
    @endpush
@endsection
