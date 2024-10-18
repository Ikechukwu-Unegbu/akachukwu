<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="All Transactions" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">All Transactions</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.transaction') }}">
                    <div class="row">
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="type">Type</label>
                                <select class="form-control" wire:model="type" name="type" id="type">
                                    <option value="">All</option>
                                    <option value="data">Data</option>
                                    <option value="airtime">Airtime</option>
                                    <option value="cable">Cable</option>
                                    <option value="electricity">Electricity</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" wire:model="startDate" name="start_date"
                                    id="start_date">
                            </div>
                        </div>
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" wire:model="endDate" name="end_date"
                                    id="end_date">
                            </div>
                        </div>
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="status">Status</label>
                                <select class="form-control" wire:model="status" name="status" id="status">
                                    <option value="">All</option>
                                    <option value="1">Success</option>
                                    <option value="0">Failed</option>
                                    <option value="2">Refunded</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="filter"></label>
                                <input type="submit" class="form-control btn btn-primary" value="Filter" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage"
                    wireSearchAction="wire:model.live=search" />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="[$status == 0 ? '#' : 'SN', 'Customer', 'Amount', 'Type', 'Date', 'Status']" />
                    <x-admin.table-body>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <th scope="row">
                                    @if ($status == 0)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="selectedUser.{{ $transaction->type }}.{{ $transaction->transaction_id }}">
                                        </div>
                                    @else
                                        {{ $loop->index + $transactions->firstItem() }}
                                    @endif
                                </th>
                                <td>{{ $transaction->user_name }}</td>
                                <td>₦{{ $transaction->amount }}</td>
                                <td>{{ Str::title($transaction->type) }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y. h:ia') }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status === 1 ? 'success' : ($transaction->status === 0 ? 'danger' : 'warning') }}">
                                        {{ $transaction->status === 1 ? 'Successful' : ($transaction->status === 0 ? 'Failed' : 'Refunded') }}</span>
                                </td>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                <x-admin.paginate :paginate=$transactions />
            </div>
        </div>
        @if ($status == 0)
            <div class="card mt-4">
                <div class="card-body p-3">
                    <button class="btn btn-primary w-25" data-bs-toggle="modal"
                        data-bs-target="#verticalycentered">Refund</button>
                    <div wire:ignore.self class="modal fade" id="verticalycentered" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Refund</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h5 class="text-center">Are you sure you want to perform this transaction refund?</h5>
                                </div>
                                <div class="modal-footer">
                                    <div class="mx-auto">
                                        <button type="button" wire:loading.remove wire:target="performRefund" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" wire:click='performRefund'>
                                            
                                            <div wire:loading.remove wire:target="performRefund">
                                                 Proceed
                                            </div>
                
                                            <div wire:loading wire:target="performRefund">  
                                                <i class="bx bx-loader-circle bx-spin"></i>  Please wait...
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
</div>
@push('title')
    Transactions / All Transactions
@endpush
