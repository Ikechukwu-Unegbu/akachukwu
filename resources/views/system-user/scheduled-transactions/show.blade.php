@extends('layouts.admin.app')
@section('content')
    <x-admin.page-title title="Transfers">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Scheduled Transactions" />
    </x-admin.page-title>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="p-1 m-1 card-title">Scheduled Transactions Mgt.</h5>
                    <div>
                        <a href="{{ route('admin.scheduled.index') }}" class="btn btn-sm btn-secondary">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6 class="card-title">Transaction Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th>Transaction ID</th>
                                <td>{{ $transaction->transaction_id }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>₦{{ number_format($transaction->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Mobile Number</th>
                                <td>{{ $transaction->mobile_number }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge
                                        @if($transaction->vendor_status == 'successful') bg-success
                                        @elseif($transaction->vendor_status == 'failed') bg-danger
                                            @else bg-warning
                                        @endif">
                                        {{ ucfirst($transaction->vendor_status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="card-title">Balance Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th>Balance Before</th>
                                <td>₦{{ number_format($transaction->balance_before, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Balance After</th>
                                <td>₦{{ number_format($transaction->balance_after, 2) }}</td>
                            </tr>
                        </table>

                        @if($transaction->logs)
                            <h6 class="mt-4">Logs</h6>
                            <pre
                                class="bg-light p-3">{{ json_encode($transaction->logs, JSON_PRETTY_PRINT) }}</pre>
                        @endif
                    </div>
                </div>


            </div>
            @if($transaction->vendor_status == 'failed')
                <div class="card-footer">
                    <div class="mt-4">
                        <a href="{{ route('admin.scheduled.retry', [$productType, $transaction->id]) }}"
                            class="btn btn-warning">
                            Retry Transaction
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </section>

    @push('title')
        Scheduled Transactions
    @endpush
@endsection
