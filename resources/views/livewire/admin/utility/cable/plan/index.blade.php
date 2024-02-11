<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Cable TV" link="{{ route('admin.utility.cable') }}" />
        <x-admin.page-title-item subtitle="Cable Plans" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="p-0 m-0 card-title" style="">{{ $vendor->name }} ({{ $cable->cable_name }}) - Cable {{ Str::plural('Plan', count($cablePlans)) }}</h5>
                    <div class="d-flex">
                        @can('create cable utility')
                        <a href="{{ route('admin.utility.cable.plan.create', [$vendor->id, $cable->id]) }}" class="btn btn-sm btn-primary"><i class="bx bx-plus-circle"></i> Add Plan</a>
                        @endcan
                        <a href="{{ route('admin.utility.cable') }}?vendor={{ $vendor->id }}" class="btn btn-sm btn-warning"><i class="bx bx-arrow-back"></i> Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Plan', 'API ID', 'Amount', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($cablePlans as $__cablePlan)
                            <tr>
                                <th scope="row">{{ $loop->index+1 }}</th>
                                <td>{{ $__cablePlan->cable->cable_name }} - (<strong>{{ $__cablePlan->package }}</strong>)</td>
                                <td>{{ $__cablePlan->cable_plan_id }}</td>
                                <td>₦{{ number_format($__cablePlan->amount, 2) }}</td>
                                <td><span class="badge bg-{{ $__cablePlan->status ? 'success' : 'danger' }}">{{ $__cablePlan->status ? 'Active' : 'Not-Active' }}</span></td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            @can('edit cable utility')
                                            <li><a href="{{ route('admin.utility.cable.plan.edit', [$vendor->id, $cable->id,$__cablePlan->id]) }}" class="dropdown-item text-secondary"><i class="bx bx-edit"></i> Edit</a></li>
                                            @endcan
                                            @can('delete cable utility')
                                            <li><a href="{{ route('admin.utility.cable.plan.destroy', [$vendor->id, $cable->id,$__cablePlan->id]) }}" class="dropdown-item text-danger"><i class="bx bx-trash"></i> DEL</a></li>
                                            @endcan
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
    </section>
</div>

@push('title')
    Utilities / Cable TV / Cable Plans
@endpush