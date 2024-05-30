<div>
    <div class="dashboard_body">
        @section('head')
        <link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}" />
        <link rel="stylesheet" href="{{asset('css/index.css')}}" />
        <link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}" />
        @endsection
        <div class="sidebar_body">
            @include('components.dasboard_sidebar')
        </div>

        <div class="dashboard_section">
            <div class="container">
                <div class="mb-5 card">
                    <div class="card-body">
                        <h5 class="">Electricity {{ Str::plural('Transaction', count($electricity_transactions)) }}</h5>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <x-perpage :perPages=$perPages wirePageAction="wire:model.live=perPage"
                            wireSearchAction="wire:model.live=search" />
                    </div>
                    <div class="card-body">
                        
                            <x-table>
                                <x-table-header
                                    :headers="['#', 'Meter No.', 'Disco', 'Amount', 'Date', 'Status', 'Action']" />
                                <x-table-body>
                                    @forelse ($electricity_transactions as $electricity_transaction)
                                    <tr>
                                        <th scope="row">{{ $loop->index + $electricity_transactions->firstItem() }}</th>
                                        <td>{{ $electricity_transaction->meter_number }}</td>
                                        <td>{{ $electricity_transaction->disco_name }}</td>
                                        <td>₦{{ $electricity_transaction->amount }}</td>
                                        <td>
                                            <small>{{ $electricity_transaction->created_at->format('M d, Y. h:ia') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $electricity_transaction->status ? 'success' : 'danger' }}">{{ $electricity_transaction->status ? 'Successful' : 'Failed' }}</span>
                                            <br />
                                            <small><span class="badge bg-success">{{ !$electricity_transaction->status ? 'Refunded' : '' }}</span></small>
                                        </td>
                                        <td>
                                            <a href="{{ route('user.transaction.electricity.receipt', $electricity_transaction->transaction_id) }}" class="btn btn-sm"> View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">No Records Found!</td>
                                    </tr>
                                    @endforelse
                                </x-table-body>
                            </x-table>
                            
                        <x-paginate :paginate=$electricity_transactions />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>