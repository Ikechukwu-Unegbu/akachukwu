<div>
    <x-admin.page-title title="APIs">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="APIs" />
        <x-admin.page-title-item subtitle="Payment" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Manage Payment Gateway</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Gateway', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($payments as $payment)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $payment->name }}</td>
                                <td><span class="badge bg-{{ $payment->status ? 'success' : 'danger' }}">{{ $payment->status ? 'Active' : 'Not-Active' }}</span></td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            @can('view payment api')
                                            <li><a href="{{ route('admin.api.payment.show', $payment->id) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i> View</a></li>
                                            @endcan
                                            @can('edit payment api')
                                            <li><a href="{{ route('admin.api.payment.edit', $payment->id) }}" class="dropdown-item text-secondary"><i class="bx bx-edit"></i> Edit</a></li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>

                <x-admin.paginate :paginate=$payments />
            </div>
        </div>
    </section>
</div>
@push('title')
    API / Payment
@endpush
