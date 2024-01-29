<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Cables" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="">Manage Cable TV</h5>
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

        @if (count($cables) > 0)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $vendors->find($vendor)->name ?? '' }} - Cable TV</h5>
                    <x-admin.table>
                        <x-admin.table-header :headers="['#', 'Cable', 'API ID', 'Cable Plan(s)', 'Status', 'Action']" />
                        <x-admin.table-body>
                            @forelse ($cables as $__cable)
                                <tr>
                                    <th scope="row">{{ $loop->index+1 }}</th>
                                    <td>{{ $__cable->cable_name }}</td>
                                    <td>{{ $__cable->cable_id }}</td>
                                    <td>{{ $__cable->plans_count }}</td>
                                    <td><span class="badge bg-{{ $__cable->status ? 'success' : 'danger' }}">{{ $__cable->status ? 'Active' : 'Not-Active' }}</span></td>
                                    <td>
                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <li><a href="{{ route('admin.utility.cable.plan', [$vendor, $__cable->id]) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i> Manage</a></li>
                                                <li><a href="{{ route('admin.utility.cable.edit', [$vendor, $__cable->id]) }}" class="dropdown-item text-secondary"><i class="bx bx-edit"></i> Edit</a></li>
                                            </ul>
                                        </div>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No records available</td>
                                </tr>
                            @endforelse
                        </x-admin.table-body>
                    </x-admin.table>
                </div>
            </div>
        @endif
    </section>
</div>
@push('title')
    Utilities / Cable TV
@endpush