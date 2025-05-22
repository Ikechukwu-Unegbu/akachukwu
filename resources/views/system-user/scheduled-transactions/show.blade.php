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
                                <th width="30%">Transaction ID</th>
                                <td>{{ $transaction->uuid }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{ ucfirst($transaction->type) }}</td>
                            </tr>
                            <tr>
                                <th>Frequency</th>
                                <td>{{ ucfirst($transaction->frequency) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge
                                                        {{ $transaction->status === 'completed' ? 'bg-success' : '' }}
                                                        {{ $transaction->status === 'failed' ? 'bg-danger' : '' }}
                                                        {{ $transaction->status === 'pending' ? 'bg-warning' : '' }}
                                                        {{ $transaction->status === 'processing' ? 'bg-warning' : '' }}
                                                        {{ $transaction->status === 'disabled' ? 'bg-danger' : '' }}">
                                        {{ ucfirst($transaction->status) }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h4>Schedule Information</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Next Run At</th>
                                <td>{{ $transaction->next_run_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Run At</th>
                                <td>{{ $transaction->last_run_at ? $transaction->last_run_at->format('Y-m-d H:i') : 'Never' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $transaction->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        </table>

                        @if($transaction->logs)
                            <div style="max-height: 30vh; overflow: scroll;">
                                <h6 class="mt-4">Logs</h6>
                                <pre class="bg-light p-3">{{ json_encode($transaction->logs, JSON_PRETTY_PRINT) }}</pre>
                            </div>
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
