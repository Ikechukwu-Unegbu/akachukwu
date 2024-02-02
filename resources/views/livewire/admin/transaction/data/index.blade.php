<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Data" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Data {{ Str::plural('Transation', count($data_transactions)) }}</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">                
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'User', 'Phone No.', 'Network', 'Data Plan', 'Amount', 'Date', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($data_transactions as $data_transaction)
                            <tr>
                                <th scope="row">{{ $loop->index+1 }}</th>
                                <td>{{ $data_transaction->user->name }}</td>
                                <td>{{ $data_transaction->mobile_number }}</td>
                                <td>{{ $data_transaction->plan_network }}</td>
                                <td>{{ $data_transaction->size }}</td>
                                <td>₦{{ $data_transaction->amount }}</td>
                                <td>{{ $data_transaction->created_at->format('M d, Y. h:ia') }}</td>
                                <td><span class="badge bg-{{ $data_transaction->status ? 'success' : 'danger' }}">{{ $data_transaction->status ? 'Successful' : 'Failed' }}</span></td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ route('admin.transaction.data.show', $data_transaction->id) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i> View</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
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