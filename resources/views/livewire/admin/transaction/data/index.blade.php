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
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">                
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Trx. ID', 'User', 'Phone No.', 'Network','Vendor', 'Data Plan', 'Amount', 'Bal. B4', 'Bal. After', 'After Refund','Discount', 'Date', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($data_transactions as $data_transaction)
                            <tr>
                                <th scope="row">{{ $loop->index+1 }}</th>
                                <td> <a  href="{{route('admin.hr.user.show', [$data_transaction->user->username])}}">{{ $data_transaction->user->username }}</a> </td>
                                <td>{{ $data_transaction->transaction_id }}</td>
                                <td>{{ $data_transaction->mobile_number }}</td>
                                <td>{{ $data_transaction->plan_network }}</td>
                                <td>{{ $data_transaction->vendor->name }}</td>
                                <td>{{ $data_transaction->size }}</td>
                                <td>₦{{ $data_transaction->amount }}</td>
                                <td>₦{{ $data_transaction->balance_before }}</td>
                                <td>₦{{ $data_transaction->balance_after }}</td>
                                <td>₦{{ $data_transaction->balance_after_refund }}</td>
                                <td>%{{ $data_transaction->discount }}</td>
                                <td>{{ $data_transaction->created_at->format('M d, Y. h:ia') }}</td>
                                <td>
                                    <span class="badge bg-{{ $data_transaction->status === 1 ? 'success' : ($data_transaction->status === 0 ? 'danger' : 'warning') }}">
                                        {{ Str::title($data_transaction->vendor_status) }}</span>
                                </td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ route('admin.transaction.data.show', $data_transaction->id) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i> View</a></li>
                                            <li><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#action-{{ $data_transaction->id }}" class="dropdown-item text-success"><i class="bx bx-code-curly"></i> Vendor Response</a></li>
                                        </ul>
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