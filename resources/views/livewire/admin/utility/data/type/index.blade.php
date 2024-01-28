@push('title')
    Utilities / Data / Data Types
@endpush

<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Data" link="{{ route('admin.utility.data') }}" />
        <x-admin.page-title-item subtitle="Data Types" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title p-0 m-0" style="">{{ $vendor->name }} ({{ $network->name }}) - Data {{ Str::plural('Type', count($dataTypes)) }}</h5>
                    <a href="{{ route('admin.utility.data') }}{{ "?vendor={$vendor->id}" }}" class="btn btn-sm btn-primary"><i class="bx bx-arrow-back"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['#', 'Data Type(s)', 'Data Plan(s)', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($dataTypes as $__dataType)
                            <tr>
                                <th scope="row">{{ $loop->index+1 }}</th>
                                <td>{{ $__dataType->name }}</td>
                                <td>{{ $__dataType->data_plans_count }}</td>
                                <td><span class="badge bg-{{ $__dataType->status ? 'success' : 'danger' }}">{{ $__dataType->status ? 'Active' : 'Not-Active' }}</span></td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ route('admin.utility.data.plan', [$vendor->id, $network->id, $__dataType->id]) }}" class="dropdown-item text-primary"><i class="bx bx-list-ul"></i> Manage</a></li>
                                            <li><a href="{{ route('admin.utility.data.type.edit', [$vendor->id, $network->id, $__dataType->id]) }}" class="dropdown-item text-secondary"><i class="bx bx-edit"></i> Edit</a></li>
                                            {{-- <li><a href="" class="dropdown-item text-danger"><i class="bx bx-trash"></i> DEL</a></li> --}}
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
            </div>
        </div>
    </section>
</div>
