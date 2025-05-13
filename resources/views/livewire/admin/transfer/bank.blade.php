<div>
    <x-admin.page-title title="Transfers">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transfer" />
        <x-admin.page-title-item subtitle="Bank Transfer" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">Bank Transfer
                </h5>
            </div>
        </div>
        <div>
            <div class="card">
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">From Date</label>
                            <input type="date" wire:model.live="dateFrom" class="form-control">
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">To Date</label>
                            <input type="date" wire:model.live="dateTo" class="form-control">
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Amount From</label>
                            <input type="number" wire:model.live="amountFrom" placeholder="Min amount" class="form-control">
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Amount To</label>
                            <input type="number" wire:model.live="amountTo" placeholder="Max amount" class="form-control">
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Bank</label>
                            <select name="bank" id="bank" class="form-select" wire:model.live="bank">
                                <option value=""></option>
                                @foreach ($banks as $__bank)
                                    <option value="{{ $__bank->code }}">{{ $__bank->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" wire:model.live="status">
                                <option value=""></option>
                                @foreach ($statuses as $_status)
                                    <option value="{{ $_status }}">{{ Str::title($_status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="float-end">
                        <button wire:click="resetFilters" class="btn btn-primary">
                            Reset Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage"
                    wireSearchAction="wire:model.live=search" />
            </div>
            <div class="card-body">
                <x-admin.table style="font-size: small;">
                    <x-admin.table-header :headers="['#', 'Trx. ID', 'Sender', 'Bank', 'Amount', 'Timestamp', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($transfers as $transfer)
                            <tr>
                                <td>{{ $loop->index + $transfers->firstItem() }}</td>
                                <td>{{ $transfer->reference_id }}</td>
                                <td>{{ $transfer->sender->name ?? 'N/A' }}</td>
                                <td>{{ $transfer->bank_name ?? 'N/A' }} <br> {{ $transfer->account_number }}</td>
                                <td>₦{{ number_format($transfer->amount, 2) }}</td>
                                <td>{{ $transfer->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <span
                                        class="badge
                                        {{ $transfer->transfer_status === 'successful' ? 'bg-success' : '' }}
                                        {{ $transfer->transfer_status === 'failed' ? 'bg-danger' : '' }}
                                        {{ $transfer->transfer_status === 'pending' ? 'bg-warning' : '' }}
                                        {{ $transfer->transfer_status === 'processing' ? 'bg-warning' : '' }}
                                        {{ $transfer->transfer_status === 'refunded' ? 'bg-warning' : '' }}">
                                        {{ ucfirst($transfer->transfer_status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.transfer.bank.details', $transfer->reference_id) }}"  class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No Records Found!</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                <x-admin.paginate :paginate=$transfers />
            </div>
        </div>
    </section>
</div>

@push('title')
    Transfer :: Bank Transfer
@endpush
