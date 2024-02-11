<div>
    <x-admin.page-title title="Settings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Roles & Permissions" link="{{ route('admin.settings.role') }}" />
        <x-admin.page-title-item subtitle="Assign Role" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-0 m-0 card-title">Assign {{ $role->name }}</h5>
            </div>
        </div>
        <form wire:submit="assign">
            <div class="card">
                <div class="card-header">
                    <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
                </div>
                <div class="card-body">
                    <x-admin.table>
                        <x-admin.table-header :headers="['#', 'Admin', 'Permissions', 'Action']" />
                        <x-admin.table-body>
                            @forelse ($administrators as $administrator)
                                <tr>
                                    <th scope="row" width="5%">
                                        <input class="form-check-input me-1" type="checkbox" value="{{ $administrator->id }}" wire:model="assignRoles.{{ $administrator->id }}" @checked(in_array($administrator->id, $assignRoles))>
                                    </th>
                                    <td>
                                        <a target="_blank" href="{{ $administrator->profileRoute() }}" class="text-decoration-none">
                                            <img src="{{ $administrator->profilePicture }}" class="img-fluid img-responsive" width="30" />
                                            {{ $administrator->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $role->permissions->whereIn('id', $administrator->permissions->pluck('id', 'id')->toArray())->count() }} of {{ $role->permissions->count() }}
                                    </td>
                                    <td>
                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <li><a href="{{ route('admin.settings.role.permission.edit', [$role->id, $administrator->username]) }}" class="dropdown-item text-primary"><i class="bx bx-edit"></i> Edit {{ Str::plural('Permission', $administrator->permissions->count()) }}</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No records available</td>
                                </tr>
                            @endforelse
                        </x-admin.table-body>
                    </x-admin.table>
                    <x-admin.paginate :paginate=$administrators />
                </div>
            </div>
            {{-- <div class="card">
                <div class="card-header">
                    <h5 class="p-0 m-0 card-title">{{ Str::plural('Permission', $role->permissions->count()) }}</h5>
                </div>
                <div class="card-body">
                    <div class="pt-3 row">
                        @foreach ($role->permissions as $permission)
                        <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model="assignPermissions.{{ $permission->id }}" value="{{ $permission->id }}" id="permission-{{ Str::slug($permission->name) }}-{{ $permission->id }}" @if (in_array($permission->id, $assignPermissions)) checked @endif>
                                <label class="form-check-label" for="permission-{{ Str::slug($permission->name) }}-{{ $permission->id }}">
                                    {{ Str::title($permission->name) }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div> --}}
            <div class="card">
                <div class="card-footer">
                    <div class="">
                        <a href="{{ route('admin.settings.role') }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="assign">
                            <i class="bx bx-x-circle"></i> 
                            Cancel
                        </a>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary btn-md">
                            <div wire:loading.remove wire:target="assign">
                                <i class="bx bx-refresh"></i>  Assign
                            </div>
                            <div wire:loading wire:target="assign">  
                                <i class="bx bx-loader-circle bx-spin"></i>  Assigning...
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>
@push('title')
Settings / Roles & Permissions / Assign Role
@endpush