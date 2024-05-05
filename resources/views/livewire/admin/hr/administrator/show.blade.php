<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="HR Mgt." />
        <x-admin.page-title-item subtitle="Administrators" link="{{ route('admin.hr.administrator') }}" />
        <x-admin.page-title-item subtitle="Show" status="true" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                        <img src="{{ $user->profilePicture }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ $user->username }}</h3>
                        <h3>{{ $user->email }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="p-0 m-0 card-title">Administrator Details</h4>
                    </div>
                    <div class="pt-2 card-body profile-overview">                        
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Address</div>
                            <div class="col-lg-9 col-md-8">{{ $user->address ? $user->address : 'N/A'  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Mobile</div>
                            <div class="col-lg-9 col-md-8">{{ $user->mobile ? $user->mobile : 'N/A'  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Gender</div>
                            <div class="col-lg-9 col-md-8">{{ $user->gender ? $user->gender : 'N/A'  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Account Balance</div>
                            <div class="col-lg-9 col-md-8">₦ {{ $user->account_balance }}</div>
                        </div>
                        
                    </div>
                </div>
                @if ($user->roles)
                <form wire:submit="update">
                    @foreach ($user->roles as $role)
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
                                <a href="{{ route('admin.hr.administrator') }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="update">
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
                    @endforeach
                </form>
                @endif
            </div>
        </div>
    </section>
</div>
@push('title')
Human Resource Mgt. / Administrators 
@endpush