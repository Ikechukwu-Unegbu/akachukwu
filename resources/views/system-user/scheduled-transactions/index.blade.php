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
                        <div class="col-md-2">
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

                        <div class="col-md-2">
                            <label>Frequencies</label>
                            <select name="frequency" class="form-control">
                                <option value="">All Frequencies</option>
                                @foreach ($frequencies as $frequency)
                                    <option value="{{ $frequency }}"
                                        {{ request('frequency') == $frequency ? 'selected' : '' }}>
                                        {{ ucfirst($frequency) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
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

                        <div class="col-md-2">
                            <label>From Date</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-2">
                            <label>To Date</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.scheduled.index') }}" class="btn btn-secondary">Reset</a>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-download"></i> Export
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.scheduled.index', ['export' => 'excel']) }}">
                                        <i class="fas fa-file-excel text-success"></i> Excel
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.scheduled.index', ['export' => 'pdf']) }}">
                                        <i class="fas fa-file-pdf text-danger"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction ID</th>
                                <th>User</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Frequency</th> 
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                @php
                                    $payload = json_decode($transaction->payload);
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                                    </td>
                                    <td>{{ $payload->transaction_id }}</td>
                                    <td>{{ $transaction->user->username ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($transaction->type) }}</td>
                                    <td>₦{{ number_format($payload->amount, 2) }}</td>
                                     <td>{{ Str::title($transaction->frequency) }}</td>
                                    <td>
                                        <span class="badge
                                                    {{ $transaction->status === 'completed' ? 'bg-success' : '' }}
                                                    {{ $transaction->status === 'failed' ? 'bg-danger' : '' }}
                                                    {{ $transaction->status === 'pending' ? 'bg-warning' : '' }}
                                                    {{ $transaction->status === 'processing' ? 'bg-warning' : '' }}
                                                    {{ $transaction->status === 'disabled' ? 'bg-danger' : '' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>                                   
                                    <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <a href="{{ route('admin.scheduled.show', $transaction->uuid) }}"
                                            class="btn btn-sm btn-primary">
                                            View
                                        </a>
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
