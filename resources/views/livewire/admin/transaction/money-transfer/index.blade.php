<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Money Transfer" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">Money Transactions
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
                                        <input class="form-check-input" type="radio" name="gridRadios"
                                            id="debit" value="debit" wire:model="action">
                                        <label class="form-check-label" for="debit">
                                            Debit
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gridRadios"
                                            id="refund" value="refund" wire:model="action">
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
                        'Type',
                        'Amount',
                        'Bal. B4',
                        'Bal. After',
                        'Bal. Refund',
                        'Date',
                        'Status',
                        'Action',
                    ]" />
                    <x-admin.table-body>
                        @forelse ($money_transactions as $money_transaction)
                            <tr>
                                <th scope="row">
                                    <div class="form-check">
                                        <input class="form-check-input form-check-lg" type="checkbox" wire:model="transactions.{{ $money_transaction->id }}" @disabled($money_transaction->type === 'internal')>
                                    </div>
                                </th>
                                <td>{{ $money_transaction->reference_id }}</td>
                                <td>{{ $money_transaction->sender->name  }}</td>
                                <td>{{ Str::title($money_transaction->type)  }}</td>
                                <td>₦{{ number_format($money_transaction->amount, 2)  }}</td>
                                <td>₦{{ number_format($money_transaction->sender_balance_before, 2)  }}</td>
                                <td>₦{{ number_format($money_transaction->sender_balance_after, 2)  }}</td>
                                <td>₦{{ number_format($money_transaction->balance_after_refund, 2)  }}</td>
                                <td>{{ $money_transaction->created_at->format('M d, Y. h:ia') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $money_transaction->status === 1 ? 'success' : ($money_transaction->status === 0 ? 'danger' : 'warning') }}">
                                        {{ Str::title($money_transaction->transfer_status) ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ route('admin.transaction.money-transfer.show', $money_transaction->id) }}"
                                                    class="dropdown-item text-primary"><i class="bx bx-list-ul"></i>
                                                    View</a></li>
                                        </ul>
                                    </div>
                            </tr>

                            <x-admin.api-response id="{{ $money_transaction->id }}" response="{{ $money_transaction->api_response }}" />
                        @empty
                            <tr>
                                <td colspan="8">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                <x-admin.paginate :paginate=$money_transactions />
            </div>
        </div>
    </section>
</div>
@push('title')
    Transactions / Money Transfer
@endpush
