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
                        <h5 class="">Education {{ Str::plural('Transaction', count($education_transactions)) }}</h5>
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
                                    :headers="['#', 'Exam', 'Quaitity', 'Amount', 'Date', 'Status', 'Action']" />
                                <x-table-body>
                                    @forelse ($education_transactions as $exam_transaction)
                                    <tr>
                                        <th scope="row">{{ $loop->index + $education_transactions->firstItem() }}</th>
                                        <td>{{ $exam_transaction->exam_name }}</td>
                                        <td>{{ $exam_transaction->quantity }}</td>
                                        <td>₦{{ $exam_transaction->amount }}</td>
                                        <td>
                                            <small>{{ $exam_transaction->created_at->format('M d, Y. h:ia') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $exam_transaction->status === 1 ? 'success' : ($exam_transaction->status === 0 ? 'danger' : 'warning') }}">
                                                {{ $exam_transaction->status === 1 ? 'Successful' : ($exam_transaction->status === 0 ? 'Failed' : 'Refunded') }}
                                            </span>
                                        </td>
                                    
                                        <td>
                                            <a href="{{ route('user.transaction.education.receipt', $exam_transaction->transaction_id) }}" class="btn btn-sm"> View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">No Records Found!</td>
                                    </tr>
                                    @endforelse
                                </x-table-body>
                            </x-table>
                            
                        <x-paginate :paginate=$education_transactions />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>