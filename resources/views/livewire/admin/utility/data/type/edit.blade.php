@push('title')
    Utilities / Data / Edit - Data Type
@endpush

<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Data" link="{{ route('admin.utility.data') }}" />
        <x-admin.page-title-item subtitle="Edit Type" status="true" />
    </x-admin.page-title>

    <section class="section">
        <form wire:submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title p-0 m-0">
                        {{ $vendor->name }} ({{ $network->name }} - {{ $type->name }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mt-4 mb-4 form-group">
                                <label for="data_type_title" class="form-label mb-2">Data Type Title</label>
                                <input type="text" name="data_type_title" class="form-control @error('data_type_title') is-invalid @enderror" wire:model="data_type_title">
                                @error('data_type_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="form-check form-switch" >
                                <input class="form-check-input" type="checkbox" id="status" wire:model="status">
                                <label class="form-check-label" for="status">Status</label>
                            </div>
                            @error('status') <span class="text-danger" style="font-size: .875em">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <a href="{{ route('admin.utility.data.type', [$vendor->id, $network->id]) }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="update">
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
    </section>
</div>