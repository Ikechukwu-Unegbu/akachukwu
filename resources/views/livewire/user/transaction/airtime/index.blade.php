



<div>
@section('head')

<link rel="stylesheet" href="{{asset('css/index.css')}}" />
<link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}" />
<link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}" />
@endsection
    <div class="dashboard_body">
        
        <div class="sidebar_body">
            @include('components.dasboard_sidebar')
        </div>

        <div class="dashboard_section">
            <div class="container">
                <div class="mb-5 card">
                    <div class="card-body">
                        <h5 class="">Airtime {{ Str::plural('Transaction', count($airtime_transactions)) }}</h5>
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
                                    :headers="['#', 'Phone No.', 'Network', 'Amount', 'Date', 'Status', 'Action']" />
                                <x-table-body>
                                    @forelse ($airtime_transactions as $airtime_transaction)
                                    <tr>
                                        <th scope="row">{{ $loop->index + $airtime_transactions->firstItem() }}</th>
                                        <td>{{ $airtime_transaction->mobile_number }}</td>
                                        <td>{{ $airtime_transaction->network_name ?? '' }}</td>
                                        <td>₦{{ $airtime_transaction->amount }}</td>
                                        <td>
                                            <small>
                                                {{ $airtime_transaction->created_at->format('M d, Y. h:i a') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $airtime_transaction->status === 1 ? 'success' : ($airtime_transaction->status === 0 ? 'danger' : 'warning') }}">
                                                {{ $airtime_transaction->status === 1 ? 'Successful' : ($airtime_transaction->status === 0 ? 'Failed' : 'Refunded') }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('user.transaction.airtime.receipt', $airtime_transaction->transaction_id) }}" class="btn btn-sm"> View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">No Records Found!</td>
                                    </tr>
                                    @endforelse
                                </x-table-body>
                            </x-table>
                            
                        <x-paginate :paginate=$airtime_transactions />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
