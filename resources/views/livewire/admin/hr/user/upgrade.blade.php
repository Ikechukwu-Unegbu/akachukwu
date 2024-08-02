<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="HR Mgt." />
        <x-admin.page-title-item subtitle="Users" link="{{ route('admin.hr.user') }}" />
        <x-admin.page-title-item subtitle="Upgrade" status="true" />
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
                <form wire:submit="update">
                    <div class="card">
                        <h5 class="card-header">
                            Upgrade User
                        </h5>
                        <div class="pt-2 card-body profile-overview">                        
                            <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                                <div class="mt-3 mb-3 form-group">
                                    <label for="level" class="form-label">Upgrade User Level</label>
                                    <select name="level" class="form-select" id="level @error('level') is-invalid @enderror" wire:model="level">
                                        <option value="ordinary" {{ $level == 'ordinary' ? 'selected' : '' }}>Ordinary</option>
                                        <option value="reseller" {{ $level == 'reseller' ? 'selected' : '' }}>Reseller</option>
                                    </select>
                                    @error('level') <span class="text-danger" style="font-size: .875em">{{ $message }}</span> @enderror
                                </div>

                                <div class="mt-3 mb-3 form-group">
                                    <label for="role" class="form-label">Tranform User Role</label>
                                    <select name="role" class="form-select" id="role @error('role') is-invalid @enderror" wire:model="role">
                                        <option value="admin" {{ $role == 'ordinary' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ $role == 'reseller' ? 'selected' : '' }}>User</option>
                                    </select>
                                    @error('role') <span class="text-danger" style="font-size: .875em">{{ $message }}</span> @enderror
                                </div>
                            </div>    
                        </div>
                        <div class="card-footer">
                            <div class="text-left">
                                <a href="{{ route('admin.hr.user') }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="update">
                                    <i class="bx bx-x-circle"></i> 
                                    Cancel
                                </a>
                                <button type="submit" wire:loading.attr="disabled" class="btn btn-primary btn-md">
                                    <div wire:loading.remove wire:target="update">
                                         Upgrade
                                    </div>
        
                                    <div wire:loading wire:target="update">  
                                        <i class="bx bx-loader-circle bx-spin"></i>  Upgrading...
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
Human Resource Mgt. / Users / Upgrade
@endpush