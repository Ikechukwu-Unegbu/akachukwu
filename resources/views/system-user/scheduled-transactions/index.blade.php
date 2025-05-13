@extends('layouts.admin.app')
@section('content')
    <x-admin.page-title title="Transfers">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Scheduled Transactions" />
    </x-admin.page-title>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">Scheduled Transactions Mgt.</h5>
            </div>
        </div>


        <div class="card">
            <div class="card-header">
                <form action="{{ route('admin.scheduled.index') }}" method="GET" class="mb-3 mt-4">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Product Type</label>
                            <select name="product_type" class="form-control">
                                <option value="">All Types</option>
                                @foreach ($productTypes as $type)
                                    <option value="{{ $type }}"
                                        {{ request('product_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>From Date</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-3">
                            <label>To Date</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.scheduled.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction ID</th>
                                <th>User</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Mobile Number</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                                    </td>
                                    <td>{{ $transaction->transaction_id }}</td>
                                    <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($transaction->product_type) }}</td>
                                    <td>₦{{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ $transaction->mobile_number }}</td>
                                    <td>
                                        <span
                                            class="badge
                                            @if ($transaction->vendor_status == 'successful') bg-success
                                            @elseif($transaction->vendor_status == 'failed') bg-danger
                                                @else bg-warning @endif">
                                            {{ ucfirst($transaction->vendor_status) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        {{-- <a href="{{ route('admin.scheduled-transactions.show', [$transaction->product_type, $transaction->id]) }}"
                                            class="btn btn-sm btn-info">
                                            View
                                        </a>
                                        @if ($transaction->vendor_status == 'failed')
                                            <a href="{{ route('admin.scheduled-transactions.retry', [$transaction->product_type, $transaction->id]) }}"
                                                class="btn btn-sm btn-warning">
                                                Retry
                                            </a>
                                        @endif --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No transactions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>

    </section>

    @push('title')
        Scheduled Transactions
    @endpush
@endsection
