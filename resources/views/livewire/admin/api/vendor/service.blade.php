<div>
    <x-admin.page-title title="APIs">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="APIs" />
        <x-admin.page-title-item subtitle="Vendors" />
        <x-admin.page-title-item subtitle="Services" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Manage Vendor Services</h5>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Manage Utility Services</div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Service', 'Vendor', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($vendor_services as $__service)
                            <tr>
                                <th scope="row" width="3%">{{ $loop->index + 1 }}</th>
                                <td width="20%">{{ Str::title($__service->service_type) }}</td>
                                <td width="20%">{{ $__service->vendor->name }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" wire:click="updateModal({{ $__service->id }})" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="bx bx-edit"></i>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                <div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update {{ Str::title($service?->service_type) }} Service</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form 
                                wire:submit="updateService"
                            >
                                <div class="modal-body">
                                    @foreach ($vendors as $__vendor)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="vendor" id="vendor-{{ $__vendor->id }}" wire:model='vendor' value="{{ $__vendor->id }}" @checked($__vendor->id === $service?->vendor?->id ? true : false)>
                                            <label class="form-check-label" for="vendor-{{ $__vendor->id }}">{{ $__vendor->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-header">Manage Airtime Services</div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Network', 'Vendor', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($networks as $__network)
                            <tr>
                                <th scope="row" width="3%">{{ $loop->index + 1 }}</th>
                                <td width="20%">{{ Str::title($__network->name) }}</td>
                                <td width="20%">{{ $__network->airtimeMapping->vendor->name ?? 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" wire:click="updateAirtimeNetworkModal('{{ $__network->name }}')" data-bs-toggle="modal" data-bs-target="#updateAirtimeNetworkModal"><i class="bx bx-edit"></i>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                <div wire:ignore.self class="modal fade" id="updateAirtimeNetworkModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update {{ Str::title($__network?->name) }} Airtime Network</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form 
                                wire:submit="updateAirtimeService"
                            >
                                <div class="modal-body">
                                    @foreach ($vendors as $__vendor)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="vendor" id="vendor-{{ $__vendor->id }}" wire:model='vendor' value="{{ $__vendor->id }}" @checked($__vendor->id === $__network->airtimeMapping?->vendor?->id ? true : false)>
                                            <label class="form-check-label" for="vendor-{{ $__vendor->id }}">{{ $__vendor->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@push('title')
    API / Vendor / Services
@endpush
