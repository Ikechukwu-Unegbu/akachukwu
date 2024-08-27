<div>
    <x-admin.page-title title="APIs">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="APIs" />
        <x-admin.page-title-item subtitle="Vendors" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Manage Vendor APIs</h5>
                    <div>
                        <a href="{{ route('admin.api.vendor.service') }}" class="btn btn-md btn-primary">Manage Services</a>
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
                    <x-admin.table-header :headers="['#', 'Vendor', 'API', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($vendors as $vendor)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $vendor->name }}</td>
                                <td>{{ $vendor->api }}</td>
                                <td><span class="badge bg-{{ $vendor->status ? 'success' : 'danger' }}">{{ $vendor->status ? 'Active' : 'Not-Active' }}</span></td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            @can('view vendor api')
                                                <li><a href="{{ route('admin.api.vendor.show', $vendor->id) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i> View</a></li>
                                            @endcan
                                            @can('edit vendor api')
                                                <li><a href="{{ route('admin.api.vendor.edit', $vendor->id) }}" class="dropdown-item text-secondary"><i class="bx bx-edit"></i> Edit</a></li>
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

                <x-admin.paginate :paginate=$vendors />
            </div>
        </div>
    </section>
</div>
@push('title')
    API / Vendor
@endpush