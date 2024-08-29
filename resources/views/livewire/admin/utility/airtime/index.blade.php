@push('title')
    Utilities / Airtime / Network
@endpush

<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Airtime" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="">Manage Airtime Network</h5>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">{{ $vendors->find($vendor)->name ?? '' }} - Airtime {{ Str::plural('Network', $networks->count()) }}</h5>
                        <div><a href="{{ route('admin.utility.airtime.discount', $vendors->find($vendor)->id) }}" class="btn btn-sm btn-primary">Set Discounts</a></div>
                    </div>
                    <x-admin.table>
                        <x-admin.table-header :headers="['#', 'Network(s)', 'API ID', 'Discounts(%)', 'Status']" />
                        <x-admin.table-body>
                            @forelse ($networks as $__network)
                                <tr>
                                    <th scope="row">{{ $loop->index+1 }}</th>
                                    <td>{{ $__network->name }}</td>
                                    <td>{{ $__network->network_id }}</td>
                                    <td>{{ $__network->airtime_discount }}</td>
                                    <td><span class="badge bg-{{ $__network->status ? 'success' : 'danger' }}">{{ $__network->status ? 'Active' : 'Not-Active' }}</span></td>
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
