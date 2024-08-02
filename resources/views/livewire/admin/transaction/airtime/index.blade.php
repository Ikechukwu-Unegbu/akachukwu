<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Airtime" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">Airtime {{ Str::plural('Transaction', count($airtime_transactions)) }}</h5>
            </div>
        </div>
        <div class="card">            
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Customer', 'Phone No.', 'Network', 'Amount', 'Date', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($airtime_transactions as $airtime_transaction)
                            <tr>
                                <th scope="row">{{ $loop->index+1 }}</th>
                                <td>{{ $airtime_transaction->user->name }}</td>
                                <td>{{ $airtime_transaction->mobile_number }}</td>
                                <td>{{ $airtime_transaction->network_name ?? '' }}</td>
                                <td>₦{{ $airtime_transaction->amount }}</td>
                                <td>{{ $airtime_transaction->created_at->format('M d, Y. h:ia') }}</td>

                                <td><span class="badge bg-{{ $airtime_transaction->status ? 'success' : 'danger' }}">{{ $airtime_transaction->status ? 'Successful' : 'Failed' }}</span></td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ route('admin.transaction.airtime.show', $airtime_transaction->id) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i> View</a></li>
                                            <li><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#action-{{ $airtime_transaction->id }}" class="dropdown-item text-success"><i class="bx bx-code-curly"></i> Vendor Response</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <x-admin.api-response id="{{ $airtime_transaction->id }}" response="{{ $airtime_transaction->api_response }}" />
                        @empty
                            <tr>
                                <td colspan="8">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                <x-admin.paginate :paginate=$airtime_transactions /> 
            </div>
        </div>
    </section>
</div>
@push('title')
    Transactions / Airtime
@endpush