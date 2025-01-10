<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Cable TV" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Cable TV {{ Str::plural('Transaction', count($cable_transactions)) }}</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">                
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Trx. ID','Vendor', 'User', 'Card Name', 'Card No.', 'Cable Plan', 'Amount','Bal B4', 'Bal After', 'After Refund','IUC', 'Date', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($cable_transactions as $cable_transaction)
                            <tr>
                                <th scope="row">
                                    <div class="form-check">
                                        <input class="form-check-input form-check-lg" type="checkbox" wire:model="transactions.{{ $cable_transaction->id }}">
                                    </div>    
                                </th>
                                <td>{{$cable_transaction->transaction_id}}</td>
                                <td><a href="{{route('admin.hr.user.show', [$cable_transaction->user->username])}}">{{$cable_transaction->vendor->name}}</a></td>
                                <td>{{$cable_transaction->customer_name}}</td>
                                <td>{{ $cable_transaction->user->name }}</td>
                                <td>{{ $cable_transaction->smart_card_number }}</td>
                                <td>
                                    {{ $cable_transaction->cable_name  }} - <small style="font-size: 13px">({{ $cable_transaction->cable_plan_name }})</small>
                                </td>
                                <td>₦{{ $cable_transaction->amount }}</td>
                                <td>₦{{ $cable_transaction->balance_before }}</td>
                                <td>₦{{ $cable_transaction->balance_after }}</td>
                                <td>₦{{ $cable_transaction->balance_after_refund }}</td>
                                <td>{{$cable_transaction->smart_card_number}}</td>
                                <td>{{ $cable_transaction->created_at->format('M d, Y. h:ia') }}</td>
                                <td>
                                    <span class="badge bg-{{ $cable_transaction->status === 1 ? 'success' : ($cable_transaction->status === 0 ? 'danger' : 'warning') }}">
                                        {{ Str::title($cable_transaction->vendor_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">                                            
                                            <li><a href="{{ route('admin.transaction.cable.show', $cable_transaction->id) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i> View</a></li>
                                            <li><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#action-{{ $cable_transaction->id }}" class="dropdown-item text-success"><i class="bx bx-code-curly"></i> Vendor Response</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <x-admin.api-response id="{{ $cable_transaction->id }}" response="{{ $cable_transaction->api_response }}" />
                        @empty
                            <tr>
                                <td colspan="5">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                <x-admin.paginate :paginate=$cable_transactions /> 
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
    </section>
</div>
@push('title')
    Transactions / Cable TV
@endpush