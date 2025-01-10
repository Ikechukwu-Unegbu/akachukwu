<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Electricity" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Electricity {{ Str::plural('Transaction', count($electricity_transactions)) }}</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">                
                <x-admin.table>
                    <x-admin.table-header :headers="['#','Trx. ID', 'User', 'Meter. Info.','Customer', 'Disco Name','Address', 'Amount','Bal. b4','Bal. After','After Rfund', 'Token','Date', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($electricity_transactions as $electricity_transaction)
                            <tr>
                                <th scope="row">
                                    <div class="form-check">
                                        <input class="form-check-input form-check-lg" type="checkbox" wire:model="transactions.{{ $electricity_transaction->id }}">
                                    </div>    
                                </th>
                                <td>{{$electricity_transaction->transaction_id}}</td>
                                <td> <a  href="{{route('admin.hr.user.show', [$electricity_transaction->user->username])}}">{{ $electricity_transaction->user->username }}</a> </td>
                                <td>{{ $electricity_transaction->meter_number }} - (<small>{{ $electricity_transaction->meter_type_name }}</small>)</td>
                                <td>{{ $electricity_transaction->customer_name }} - {{$electricity_transaction->customer_mobile_number}}</td>
                                <td>{{ $electricity_transaction->disco_name }}</td>
                                <td>{{$electricity_transaction->customer_address}}</td>
                                <td>₦{{ $electricity_transaction->amount }}</td>
                                <td>₦{{ $electricity_transaction->balance_before }}</td>
                                <td>₦{{ $electricity_transaction->balance_after }}</td>
                                <td>₦{{ $electricity_transaction->balance_after_refund }}</td>
                                <td>{{$electricity_transaction->token}}</td>
                                <td>{{ $electricity_transaction->created_at->format('M d, Y. h:ia') }}</td>
                                <td>
                                    <span class="badge bg-{{ $electricity_transaction->status === 1 ? 'success' : ($electricity_transaction->status === 0 ? 'danger' : 'warning') }}">
                                        {{ Str::title($electricity_transaction->vendor_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ route('admin.transaction.electricity.show', $electricity_transaction->id) }}" class="dropdown-item text-primary"><i class="bx bx-list"></i> View</a></li>
                                            <li><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#action-{{ $electricity_transaction->id }}" class="dropdown-item text-success"><i class="bx bx-code-curly"></i> Vendor Response</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <x-admin.api-response id="{{ $electricity_transaction->id }}" response="{{ $electricity_transaction->api_response }}" />
                        @empty
                            <tr>
                                <td colspan="8">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body> 
                </x-admin.table>
                <x-admin.paginate :paginate=$electricity_transactions /> 
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
    Transactions / Electricity
@endpush