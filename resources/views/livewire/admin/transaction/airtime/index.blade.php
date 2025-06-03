<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Airtime" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">Airtime {{ Str::plural('Transaction', count($airtime_transactions)) }}
                </h5>
            </div>
        </div>
        <div>
            <div class="card">
                <div class="card-body p-4">
                    <button class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#reimbursement">Reimbursement</button>
                    <div wire:ignore.self class="modal fade" id="reimbursement" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Transaction Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gridRadios" id="debit"
                                            value="debit" wire:model="action">
                                        <label class="form-check-label" for="debit">
                                            Debit
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gridRadios" id="refund"
                                            value="refund" wire:model="action">
                                        <label class="form-check-label" for="refund">
                                            Refund
                                        </label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="mx-auto">
                                        <button type="button" wire:loading.remove wire:target="performReimbursement"
                                            class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" wire:click='performReimbursement'>

                                            <div wire:loading.remove wire:target="performReimbursement">
                                                Proceed
                                            </div>

                                            <div wire:loading wire:target="performReimbursement">
                                                <i class="bx bx-loader-circle bx-spin"></i> Please wait...
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    <x-admin.table-header :headers="[
        '#',
        'Trx. ID',
        'Customer',
        'Phone No.',
        'Network',
        'Vendor',
        'Amount',
        'Discount',
        'Refunded',
        'Date',
        'Status',
        'Action',
    ]" />
                    <x-admin.table-body>
                        @forelse ($airtime_transactions as $airtime_transaction)
                            <tr>
                                <th scope="row">
                                    <div class="form-check">
                                        <input class="form-check-input form-check-lg" type="checkbox"
                                            wire:model="transactions.{{ $airtime_transaction->id }}">
                                    </div>
                                </th>
                                <td>{{ $airtime_transaction->transaction_id }}</td>
                                <td> <a
                                        href="{{ route('admin.hr.user.show', [$airtime_transaction->user->username]) }}">{{ $airtime_transaction->user->username }}</a>
                                </td>

                                <td>{{ $airtime_transaction->mobile_number }}</td>
                                <td>{{ $airtime_transaction->network_name ?? '' }}</td>
                                <td>{{ $airtime_transaction->vendor->name ?? '' }}</td>
                                <td>₦{{ $airtime_transaction->amount }}</td>
                                <td>%{{ $airtime_transaction->discount }}</td>
                                {{--<td>₦{{ $airtime_transaction->balance_before }}</td>--}}
                                <td>{{ Str::lower($airtime_transaction->vendor_status) === 'refunded' ? 'Yes' : 'No' }}</td>
                                <td>{{ $airtime_transaction->created_at->format('M d, Y. h:ia') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $airtime_transaction->status === 1 ? 'success' : ($airtime_transaction->status === 0 ? 'danger' : 'warning') }}">
                                        {{ Str::title($airtime_transaction->vendor_status) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('admin.transaction.airtime.show', $airtime_transaction->id) }}"
                                            class="btn btn-sm btn-primary me-3">
                                            View</a>
                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#action-{{ $airtime_transaction->id }}"
                                            class="btn btn-sm btn-secondary">
                                            Vendor Response</a>
                                    </div>
                                </td>
                            </tr>

                            <x-admin.api-response id="{{ $airtime_transaction->id }}"
                                response="{{ $airtime_transaction->api_response }}" />
                        @empty
                            <tr>
                                <td colspan="8">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                {{ $airtime_transactions->links() }}
            </div>
        </div>
    </section>
</div>
@push('title')
    Transactions / Airtime
@endpush
