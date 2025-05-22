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
                        <h6 class="card-title">Schedule Information</h6>
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
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span
                                        class="badge
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
                        <h4 class="card-title">Schedule Logs</h4>
                        @if($transaction->logs)
                            <div id="logsContainer" style="max-height: 30vh; overflow: auto;">
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

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title">{{ ucfirst($transaction->type) }} Transactions</h4>
                    <form action="{{ route('admin.scheduled.show', $transaction->uuid) }}" method="GET" class="d-inline">
                        <select name="perPage" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
                            @foreach([50, 100, 200] as $perPage)
                                <option value="{{ $perPage }}" {{ request('perPage', 50) == $perPage ? 'selected' : '' }}>
                                    {{ $perPage }} per page
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <x-admin.table>
                        <x-admin.table-header :headers="['Trx. ID', 'Phone No.', 'Network', 'Vendor', 'Data Plan', 'Amount', 'Bal. B4', 'Bal. After', 'After Refund', 'Discount', 'Date', 'Status']" />
                        <x-admin.table-body>
                            @forelse ($latestTransactions as $__transaction)
                                <tr>
                                    <td>{{ $__transaction->transaction_id }}</td>
                                    <td>{{ $__transaction->mobile_number }}</td>
                                    <td>{{ $__transaction->plan_network }}</td>
                                    <td>{{ $__transaction->vendor->name }}</td>
                                    <td>{{ $__transaction->size }}</td>
                                    <td>₦{{ $__transaction->amount }}</td>
                                    <td>₦{{ $__transaction->balance_before }}</td>
                                    <td>₦{{ $__transaction->balance_after }}</td>
                                    <td>₦{{ $__transaction->balance_after_refund }}</td>
                                    <td>%{{ $__transaction->discount }}</td>
                                    <td>{{ $__transaction->created_at->format('M d, Y. h:ia') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $__transaction->status === 1 ? 'success' : ($__transaction->status === 0 ? 'danger' : 'warning') }}">
                                            {{ Str::title($__transaction->vendor_status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No records available</td>
                                </tr>
                            @endforelse
                        </x-admin.table-body>
                    </x-admin.table>

                    <div class="mt-3">
                        {{ $latestTransactions->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title p-2 m-0">Admin Notes</h4>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    @csrf
                    <div class="p-3">
                        <textarea name="adminNotes" id="adminNotes" rows="3" class="form-control"></textarea>
                    </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <div class="pt-4">
                        <button type="submit" name="action" value="retry" class="btn btn-sm btn-primary">
                            Retry Transfer
                        </button>
                        <button type="submit" name="action" value="reverse" class="btn btn-sm btn-warning">
                            Reverse Transfer
                        </button>
                        <button type="submit" name="action" value="flag" class="btn btn-sm btn-danger">
                            Flag Transaction
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>

    </section>

    @push('title')
        Scheduled Transactions
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const logsContainer = document.getElementById('logsContainer');
                if (logsContainer) {
                    logsContainer.scrollTop = logsContainer.scrollHeight;
                    logsContainer.scrollTo({
                        top: logsContainer.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            });
        </script>
    @endpush
@endsection
