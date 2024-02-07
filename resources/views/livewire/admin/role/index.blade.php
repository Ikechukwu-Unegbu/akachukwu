<div>
    <x-admin.page-title title="Settings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Roles" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Manage Roles</h5>
                    <div class="">
                        <a href="{{ route('admin.settings.role.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus-circle"></i> Add Role</a>
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
                    <x-admin.table-header :headers="['#', 'Role', 'Permission(s)', 'User(s)', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($roles as $role)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->permissions->count() }}</td>
                                <td>{{ $role->users->count() }}</td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a href="{{ route('admin.settings.role.edit', $role->id) }}" class="dropdown-item text-primary"><i class="bx bx-edit"></i> Edit</a></li>
                                            <li><a href="" class="dropdown-item text-danger"><i class="bx bx-trash"></i> DEL</a></li>
                                            <li><a href="" class="dropdown-item text-secondary"><i class="bx bx-list-ul"></i> Assign Permission</a></li>
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
                <x-admin.paginate :paginate=$roles />
            </div>
        </div>
    </section>
</div>
@push('title')
    Settings / Role
@endpush