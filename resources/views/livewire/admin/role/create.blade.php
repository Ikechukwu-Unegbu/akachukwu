<div>
    <x-admin.page-title title="Settings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Roles" link="{{ route('admin.settings.role') }}" />
        <x-admin.page-title-item subtitle="Create" status="true" />
    </x-admin.page-title>

    <section class="section">
        <form wire:submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h5 class="p-0 m-0 card-title">
                        Add Role
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mt-3 mb-3 form-group">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="p-0 m-0 card-title">
                        Assign Permission(s)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-3 col-lg-3 col-sm-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="assign" value="{{ $permission->id }}" id="permission-{{ Str::slug($permission->name) }}-{{ $permission->id }}">
                                    <label class="form-check-label" for="permission-{{ Str::slug($permission->name) }}-{{ $permission->id }}">
                                      {{ Str::title($permission->name) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('assign')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card">
                <div class="card-footer">
                    <div class="">
                        <a href="{{ route('admin.settings.role') }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="store">
                            <i class="bx bx-x-circle"></i> 
                            Cancel
                        </a>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary btn-md">
                            <div wire:loading.remove wire:target="store">
                                Submit
                            </div>
                            <div wire:loading wire:target="store">  
                                <i class="bx bx-loader-circle bx-spin"></i>  Submitting...
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

@push('title')
Settings / Role / Create
@endpush