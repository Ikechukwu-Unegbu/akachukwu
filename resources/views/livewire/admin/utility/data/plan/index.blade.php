@push('title')
    Utilities / Data / Data Plans
@endpush

<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Data" link="{{ route('admin.utility.data') }}" />
        <x-admin.page-title-item subtitle="Data Plans" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="p-0 m-0 card-title" style="">{{ $vendor->name }} ({{ $network->name }} - {{ $type->name }}) - Data {{ Str::plural('Plan', count($dataPlans)) }}</h5>
                    <div class="d-flex">
                        @can ('create data utility')
                        <a href="{{ route('admin.utility.data.plan.create', [$vendor->id, $network->id, $type->id]) }}" class="btn btn-sm btn-primary"><i class="bx bx-plus-circle"></i> Add Plan</a>
                        @endcan
                        <a href="{{ route('admin.utility.data.type', [$vendor->id, $network->id]) }}" class="btn btn-sm btn-warning"><i class="bx bx-arrow-back"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Data Plan(s)', 'API ID', 'Amount', 'Validity', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($dataPlans as $__dataPlan)
                            <tr>
                                <th scope="row">{{ $loop->index+1 }}</th>
                                <td>{{ $__dataPlan->type->name }} - (<strong>{{ $__dataPlan->size }}</strong>)</td>
                                <td>{{ $__dataPlan->data_id }}</td>
                                <td>₦{{ number_format($__dataPlan->amount, 2) }}</td>
                                <td>{{ $__dataPlan->validity }}</td>
                                <td><span class="badge bg-{{ $__dataPlan->status ? 'success' : 'danger' }}">{{ $__dataPlan->status ? 'Active' : 'Not-Active' }}</span></td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            @can ('edit data utility')
                                            <li><a href="{{ route('admin.utility.data.plan.edit', [$vendor->id, $network->id, $type->id, $__dataPlan->id]) }}" class="dropdown-item text-secondary"><i class="bx bx-edit"></i> Edit</a></li>
                                            @endcan
                                            @can ('delete data utility')
                                            <li><a href="{{ route('admin.utility.data.plan.destroy', [$vendor->id, $network->id, $type->id, $__dataPlan->id]) }}" class="dropdown-item text-danger"><i class="bx bx-trash"></i> DEL</a></li>
                                            @endcan
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
    </section>
</div>
