@push('title')
    Utilities / Data
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
                <h5 class="">Manage Data</h5>
            </div>
            <div class="card-body">
                <div class="row mt-3">
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
                                        <a href="{{ route('admin.utility.data.type', [$__network->id, $vendor]) }}" class="btn btn-sm btn-primary"><i class="bx bx-list-ul"></i> Manage</a>
                                        <a href="{{ route('admin.utility.data.network.edit', [$__network->id, $vendor]) }}" class="btn btn-sm btn-secondary"><i class="bx bx-edit"></i> Edit</a>
                                        <button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i> DEL</button>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($networks) }}">No records available</td>
                                </tr>
                            @endforelse
                        </x-admin.table-body>
                    </x-admin.table>
                </div>
            </div>
        @endif
    </section>
</div>
