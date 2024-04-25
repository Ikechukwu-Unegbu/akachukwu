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
                        <h5 class="">Data {{ Str::plural('Transaction', count($data_transactions)) }}</h5>
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
                                    :headers="['#', 'Phone No.', 'Network', 'Plan', 'Amount', 'Date', 'Status', 'Action']" />
                                <x-table-body>
                                    @forelse ($data_transactions as $data_transaction)
                                    <tr>
                                        <th scope="row">{{ $loop->index + $data_transactions->firstItem() }}</th>
                                        <td>{{ $data_transaction->mobile_number }}</td>
                                        <td>{{ $data_transaction->plan_network }} ({{ $data_transaction->data_type->name ?? ''}})</td>
                                        <td>{{ $data_transaction->size }}</td>
                                        <td>₦{{ $data_transaction->amount }}</td>
                                        <td>
                                            <small>{{ $data_transaction->created_at->format('M d, Y. h:ia') }}</small>
                                        </td>
                                        <td><span class="badge bg-{{ $data_transaction->status ? 'success' : 'danger' }}">{{ $data_transaction->status ? 'Successful' : 'Failed' }}</span></td>
                                    
                                        <td>
                                            <a href="{{ route('user.transaction.data.receipt', $data_transaction->transaction_id) }}" class="btn btn-sm"> View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">No Records Found!</td>
                                    </tr>
                                    @endforelse
                                </x-table-body>
                            </x-table>
                            
                        <x-paginate :paginate=$data_transactions />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>