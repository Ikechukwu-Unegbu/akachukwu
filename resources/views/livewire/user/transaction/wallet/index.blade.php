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
                        <h5 class="">Wallet Summary</h5>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <x-perpage :perPages=$perPages wirePageAction="wire:model.live=perPage"  />
                    </div>
                    <div class="card-body">                        
                            <x-table>
                                <x-table-header :headers="['#', 'Reference', 'Gateway', 'Amount', 'Date', 'Status']" />
                                <x-table-body>
                                    @forelse ($wallet_transactions as $wallet_transaction)
                                    <tr>
                                        <th scope="row">{{ $loop->index + $wallet_transactions->firstItem() }}</th>
                                        <td>
                                            <small>{{ $wallet_transaction->reference_id }}</small>    
                                        </td>
                                        <td>{{ Str::title($wallet_transaction->gateway_type) }}</td>
                                        <td>₦ {{ number_format($wallet_transaction->amount, 2) }}</td>
                                        <td>
                                            @php $createdAt = \Carbon\Carbon::parse($wallet_transaction->created_at); @endphp
                                            <small>{{ $createdAt->format('M d, Y. h:ia') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $wallet_transaction->status ? 'success' : 'danger' }}">
                                                {{ $wallet_transaction->status ? 'Successful' : 'Failed' }}
                                                @if($wallet_transaction->gateway_type==='Vastel' && $wallet_transaction->type == false)
                                                    <small class="text-xs">Debit</small>
                                                @endif 
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">No Records Found!</td>
                                    </tr>
                                    @endforelse
                                </x-table-body>
                            </x-table>
                            
                        <x-paginate :paginate=$wallet_transactions />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>