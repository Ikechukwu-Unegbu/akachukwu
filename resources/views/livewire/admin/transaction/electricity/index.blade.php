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
                    <x-admin.table-header :headers="['#', 'User', 'Meter No.', 'Disco Name', 'Amount', 'Date', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($electricity_transactions as $electricity_transaction)
                            <tr>
                                <th scope="row">{{ $loop->index+1 }}</th>
                                <td>{{ $electricity_transaction->user->name }}</td>
                                <td>{{ $electricity_transaction->meter_number }} - (<small>{{ $electricity_transaction->meter_type_name }}</small>)</td>
                                <td>{{ $electricity_transaction->disco_name }}</td>
                                <td>₦{{ $electricity_transaction->amount }}</td>
                                <td>{{ $electricity_transaction->created_at->format('M d, Y. h:ia') }}</td>
                                <td><span class="badge bg-{{ $electricity_transaction->status ? 'success' : 'danger' }}">{{ $electricity_transaction->status ? 'Successful' : 'Failed' }}</span></td>
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
    </section>
</div>
@push('title')
    Transactions / Electricity
@endpush