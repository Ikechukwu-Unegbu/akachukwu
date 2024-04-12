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
                        <h5 class="">Cable {{ Str::plural('Transaction', count($cable_transactions)) }}</h5>
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
                                    :headers="['#', 'Card/IUC', 'Plan', 'Amount', 'Date', 'Status', 'Action']" />
                                <x-table-body>
                                    @forelse ($cable_transactions as $cable_transaction)
                                    <tr>
                                        <th scope="row">{{ $loop->index + $cable_transactions->firstItem() }}</th>
                                        <td>{{ $cable_transaction->smart_card_number }}</td>
                                        <td>{{ $cable_transaction->cable_plan_name }}</td>
                                        <td>₦{{ $cable_transaction->amount }}</td>
                                        <td>
                                            <small>{{ $cable_transaction->created_at->format('M d, Y. h:ia') }}</small>
                                        </td>
                                        <td><span class="badge bg-{{ $cable_transaction->status ? 'success' : 'danger' }}">{{ $cable_transaction->status ? 'Successful' : 'Failed' }}</span></td>
                                        <td>
                                            <a href="{{ route('user.transaction.cable.receipt', $cable_transaction->transaction_id) }}" class="btn btn-sm"> View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">No Records Found!</td>
                                    </tr>
                                    @endforelse
                                </x-table-body>
                            </x-table>
                            
                        <x-paginate :paginate=$cable_transactions />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>