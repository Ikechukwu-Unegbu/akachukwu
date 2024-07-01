<div>
    <x-admin.page-title title="Reseller Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transactions" />
        <x-admin.page-title-item subtitle="Reseller Discounts" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="p-0 m-0 card-title" style="">Reseller Discounts</h5>
                    <div class="d-flex">
                        <a href="{{ route('admin.transaction.reseller.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus"></i> Add Discount</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Type', 'Discount', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($reseller_discounts as $reseller_discount)
                            <tr>
                                <th scope="row">{{ $loop->index + $reseller_discounts->firstItem() }}</th>
                                <td>{{ Str::title($reseller_discount->type) }}</td>
                                <td>{{ $reseller_discount->discount }}%</td>
                                <td><span class="badge bg-{{ $reseller_discount->status ? 'success' : 'danger' }}">{{ $reseller_discount->status ? 'Active' : 'Not-Active' }}</span></td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ $reseller_discount->editRoute() }}" class="dropdown-item text-primary"><i class="bx bx-edit"></i> Edit</a></li>
                                             <li><a href="{{ $reseller_discount->deleteRoute() }}" class="dropdown-item text-danger"><i class="bx bx-trash"></i>DEL</a></li>
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

                <x-admin.paginate :paginate=$reseller_discounts />
            </div>
        </div>
    </section>
</div>

@push('title')
Transactions / Reseller Discounts
@endpush