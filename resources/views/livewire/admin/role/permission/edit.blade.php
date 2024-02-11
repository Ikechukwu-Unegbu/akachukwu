<div>
    <x-admin.page-title title="Settings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Roles & Permissions" link="{{ route('admin.settings.role') }}" />
        <x-admin.page-title-item subtitle="{{ $role->name }}" link="{{ route('admin.settings.role.assign', $role->id) }}" />
        <x-admin.page-title-item subtitle="Edit {{ Str::plural('Permission', $role->permissions->count()) }}" status="true" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                        <img src="{{ $user->profile_picture }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ Str::title($user->role) }}</h3>
                        <div class="mt-2 social-links">
                            <a href="javascript:void(0)" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="javascript:void(0)" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="javascript:void(0)" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="javascript:void(0)" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">
                <form wire:submit="update">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="p-0 m-0 card-title">{{ Str::plural('Permission', $role->permissions->count()) }}</h4>
                        </div>
                        <div class="pt-3 card-body">
                            <div class="pt-3 row">
                                @foreach ($role->permissions as $permission)
                                    <div class="mb-2 col-md-4 col-lg-4 col-sm-4 col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="assignPermissions.{{ $permission->id }}" value="{{ $permission->id }}" id="permission-{{ Str::slug($permission->name) }}-{{ $permission->id }}" 
                                            @checked(in_array($permission->id, $assignPermissions))>
                                            <label class="form-check-label" for="permission-{{ Str::slug($permission->name) }}-{{ $permission->id }}">
                                                {{ Str::title($permission->name) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-footer">
                            <div class="">
                                <a href="{{ route('admin.settings.role.assign', $role->id) }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="update">
                                    <i class="bx bx-x-circle"></i> 
                                    Cancel
                                </a>
                                <button type="submit" wire:loading.attr="disabled" class="btn btn-primary btn-md">
                                    <div wire:loading.remove wire:target="update">
                                        <i class="bx bx-refresh"></i>  Update
                                    </div>
                                    <div wire:loading wire:target="update">  
                                        <i class="bx bx-loader-circle bx-spin"></i>  Updating...
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
@push('title')
Settings / Roles & Permissions / Edit - Permission
@endpush