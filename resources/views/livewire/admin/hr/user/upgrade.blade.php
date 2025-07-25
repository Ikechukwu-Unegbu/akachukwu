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
                                    <label for="role" class="form-label">Tranform Role</label>
                                    <select name="role" class="form-select" id="role @error('role') is-invalid @enderror" wire:model.live="role">
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                    @error('role') <span class="text-danger" style="font-size: .875em">{{ $message }}</span> @enderror
                                </div>
                                @if ($assign_role_action)
                                <div class="mt-3 mb-3 form-group">
                                    <label for="role" class="form-label">Assign Role</label>
                                    <select name="assign_role" class="form-select" id="assign_role @error('assign_role') is-invalid @enderror" wire:model="assign_role">
                                        <option value="">-------</option>
                                        @foreach ($roles as $__role)
                                            <option value="{{ $__role->id }}">{{ $__role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('assign_role') <span class="text-danger" style="font-size: .875em">{{ $message }}</span> @enderror
                                </div>
                                @endif
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

                <form wire:submit="updateTransferLimit">
                    <div class="card">
                        <h5 class="card-header">
                            Upgrade User Transfer Limit
                        </h5>
                        <div class="pt-2 card-body profile-overview">
                            <div class="row">
                                <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                                    <div class="mt-3 mb-3 form-group">
                                        <label for="single_transfer_limit" class="form-label">Single Transfer Limit</label>
                                        <input type="number" class="form-control @error('single_transfer_limit') is-invalid @enderror"
                                            id="single_transfer_limit" name="single_transfer_limit"
                                            wire:model="single_transfer_limit" min="0" step="0.01" placeholder="Enter single transfer limit">
                                        @error('single_transfer_limit')
                                            <span class="text-danger" style="font-size: .875em">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mt-3 mb-3 form-group">
                                        <label for="daily_transfer_limit" class="form-label">Daily Transfer Limit</label>
                                        <input type="number" class="form-control @error('daily_transfer_limit') is-invalid @enderror"
                                            id="daily_transfer_limit" name="daily_transfer_limit"
                                            wire:model="daily_transfer_limit" min="0" step="0.01" placeholder="Enter daily transfer limit">
                                        @error('daily_transfer_limit')
                                            <span class="text-danger" style="font-size: .875em">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-left">
                                <button type="submit" wire:loading.attr="disabled" class="btn btn-primary btn-md">
                                    <div wire:loading.remove wire:target="updateTransferLimit">
                                         Upgrade
                                    </div>

                                    <div wire:loading wire:target="updateTransferLimit">
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
