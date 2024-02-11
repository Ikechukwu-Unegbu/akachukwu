@push('title')
    Utilities / Data / Network
@endpush

<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Data" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="">Manage Data Network</h5>
            </div>
            <div class="card-body">
                <div class="mt-3 row">
                    <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                        <div class="form-group">
                            <label class="form-label">Vendor</label>
                            <select class="form-select" wire:model.live="vendor">
                                <option value="">---- Select Vendor -----</option>
                                @forelse ($vendors as $__vendor)
                                    <option value="{{ $__vendor->id }}">{{ $__vendor->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (count($networks) > 0)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $vendors->find($vendor)->name ?? '' }} - Data {{ Str::plural('Network', $networks->count()) }}</h5>
                    <x-admin.table>
                        <x-admin.table-header :headers="['#', 'Network(s)', 'API ID', 'Data Type(s)', 'Data Plan(s)', 'Status', 'Action']" />
                        <x-admin.table-body>
                            @forelse ($networks as $__network)
                                <tr>
                                    <th scope="row">{{ $loop->index+1 }}</th>
                                    <td>{{ $__network->name }}</td>
                                    <td>{{ $__network->network_id }}</td>
                                    <td>{{ $__network->data_types_count }}</td>
                                    <td>{{ $__network->data_plans_count }}</td>
                                    <td><span class="badge bg-{{ $__network->status ? 'success' : 'danger' }}">{{ $__network->status ? 'Active' : 'Not-Active' }}</span></td>
                                    <td>
                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                @can ('view data utility')
                                                <li><a href="{{ route('admin.utility.data.type', [$vendor, $__network->id]) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i> Manage</a></li>
                                                @endcan
                                                @can ('edit data utility')
                                                <li><a href="{{ route('admin.utility.data.network.edit', [$vendor, $__network->id]) }}" class="dropdown-item text-secondary"><i class="bx bx-edit"></i> Edit</a></li>
                                                @endcan
                                                {{-- <li><a href="" class="dropdown-item text-danger"><i class="bx bx-trash"></i> DEL</a></li> --}}
                                            </ul>
                                        </div>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No records available</td>
                                </tr>
                            @endforelse
                        </x-admin.table-body>
                    </x-admin.table>
                </div>
            </div>
        @endif
    </section>
</div>
