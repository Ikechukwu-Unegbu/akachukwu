<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="All Transactions" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">All Transactions</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.transaction') }}">
                    <div class="row">
                        <div class="col-md-3 col-6 col-lg-3">
                            <div>
                                <label for="type">Type</label>
                                <select class="form-control" name="type" id="type">
                                    <option value="">All</option>
                                    <option value="data">Data</option>
                                    <option value="airtime">Airtime</option>
                                    <option value="cable">Cable</option>
                                    <option value="electricity">Electricity</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 col-lg-3">
                            <div>
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" name="start_date" id="start_date">
                            </div>
                        </div>
                        <div class="col-md-3 col-6 col-lg-3">
                            <div>
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" name="end_date" id="end_date">
                            </div>
                        </div>
                        <div class="col-md-3 col-6 col-lg-3">
                            <div>
                                <label for="filter"></label>
                                <input type="submit" class="form-control btn btn-primary" value="Filter"/>
                            </div>
                        </div>
                    </div>                    
                </form>
            </div>
        </div>
        <div class="card">
            

            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage"
                wireSearchAction="wire:model.live=search" />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Customer', 'Amount', 'Type', 'Date', 'Status']" />
                    <x-admin.table-body>
                        @forelse ($transactions as $transaction)
                        <tr>
                            <th scope="row">{{ $loop->index+$transactions->firstItem() }}</th>
                            <td>{{ $transaction->user_name }}</td>
                            <td>₦{{ $transaction->amount }}</td>
                            <td>{{ Str::title($transaction->type) }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y. h:ia') }}</td>
                            <td>
                                <span class="badge bg-{{ $transaction->status ? 'success' : 'danger' }}">
                                {{ $transaction->status ? 'Successful' : 'Failed' }}</span></td>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">No records available</td>
                        </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                <x-admin.paginate :paginate=$transactions />
            </div>
        </div>
    </section>
</div>
@push('title')
Transactions / All Transactions
@endpush