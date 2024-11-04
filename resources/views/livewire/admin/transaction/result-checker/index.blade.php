<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="E-PINs" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">E-PINs {{ Str::plural('Transaction', count($result_checker_transactions)) }}</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">                
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'User', 'Exam', 'Quantity', 'Amount', 'Date', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($result_checker_transactions as $result_checker)
                            <tr>
                                <th scope="row">{{ $loop->index+1 }}</th>
                                <td> <a  href="{{route('admin.hr.user.show', [$result_checker->user->username])}}">{{ $result_checker->user->username }}</a> </td>
                                <td>{{ $result_checker->exam_name }}</td>
                                <td>{{ $result_checker->quantity }}</td>
                                <td>₦{{ $result_checker->amount }}</td>
                                <td>{{ $result_checker->created_at->format('M d, Y. h:ia') }}</td>
                                <td>
                                    <span class="badge bg-{{ $result_checker->status === 1 ? 'success' : ($result_checker->status === 0 ? 'danger' : 'warning') }}">
                                        {{ $result_checker->status === 1 ? 'Successful' : ($result_checker->status === 0 ? 'Failed' : 'Refunded') }}
                                    </span>                                
                                </td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ route('admin.transaction.result-checker.show', $result_checker->id) }}" class="dropdown-item text-primary"><i class="bx bx-list"></i> View</a></li>
                                            <li><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#action-{{ $result_checker->id }}" class="dropdown-item text-success"><i class="bx bx-code-curly"></i> Vendor Response</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <x-admin.api-response id="{{ $result_checker->id }}" response="{{ $result_checker->api_response }}" />
                        @empty
                            <tr>
                                <td colspan="8">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body> 
                </x-admin.table>
                <x-admin.paginate :paginate=$result_checker_transactions /> 
            </div>
        </div>
    </section>
</div>
@push('title')
    Transactions / E-PINs
@endpush