<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Data" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Data {{ Str::plural('Transaction', count($data_transactions)) }}</h5>
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
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 
                
                    'User', 
                    'Network',
                    'Type',
                    'Plan',
                    'Phone No.', 
                    'Amount', 
                   
                    'Status', 
                        'Trx. ID', 
                         'Vendor', 
                         'Refunded',
                    'Discount',
                     'Date', 
                     
                     'Action']" />
                    <x-admin.table-body>
                        @forelse ($data_transactions as $data_transaction)
                            <tr>
                                <th scope="row">
                                    <div class="form-check">
                                        <input class="form-check-input form-check-lg" type="checkbox" wire:model="transactions.{{ $data_transaction->id }}">
                                    </div>
                                </th>
                                <td> <a  href="{{route('admin.hr.user.show', [$data_transaction->user->username])}}">{{ $data_transaction->user->username }}</a> </td>
                                <td>{{ $data_transaction->plan_network }}</td>
                                <td>{{ $data_transaction->data_type->name }}</td>
                                <td>{{ $data_transaction->data_plan->size }}</td>
                                <td>{{ $data_transaction->mobile_number }}</td>
                                <td>₦{{ $data_transaction->amount }}</td>
                                <td>
                                    <span class="badge bg-{{ $data_transaction->status === 1 ? 'success' : ($data_transaction->status === 0 ? 'danger' : 'warning') }}">
                                        {{ Str::title($data_transaction->vendor_status) }}</span>
                                </td>
                                <td>{{ $data_transaction->transaction_id }}</td>
                                <td>{{ $data_transaction->vendor->name }}</td>
                                <td>{{ Str::lower($data_transaction->vendor_status) === 'refunded' ? 'Yes' : 'No' }}</td>
                                
                                
                              
                                <td>%{{ $data_transaction->discount }}</td>

                                <td>{{ $data_transaction->created_at->format('M d, Y. h:ia') }}</td>
                          
                                <td>

                                 <div class="d-flex align-items-center">
                                        <a href="{{ route('admin.transaction.data.show', $data_transaction->id) }}"
                                            class="btn btn-sm btn-primary me-3">
                                            View</a>
                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#action-{{ $data_transaction->id }}"
                                            class="btn btn-sm btn-secondary">
                                            Response</a>
                                </div>

                                </td>
                            </tr>
                            <x-admin.api-response id="{{ $data_transaction->id }}" response="{{ $data_transaction->api_response }}" />
                        @empty
                            <tr>
                                <td colspan="9">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>

                <x-admin.paginate :paginate=$data_transactions />
            </div>
        </div>
    </section>
</div>
@push('title')
    Transactions / Data
@endpush
