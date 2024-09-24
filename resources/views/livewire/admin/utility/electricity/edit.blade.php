

<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Electricity" link="{{ route('admin.utility.electricity') }}" />
        <x-admin.page-title-item subtitle="Edit Electricity" status="true" />
    </x-admin.page-title>

    <section class="section">
        <form wire:submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h5 class="p-0 m-0 card-title">
                        {{ $vendor->name }} ({{ $electricity->disco_name }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mt-3 mb-3 form-group">
                                <label for="disco_id" class="form-label">API ID / Disco ID</label>
                                <input type="text" name="disco_id" class="form-control @error('disco_id') is-invalid @enderror" wire:model="disco_id">
                                @error('disco_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-4 form-group">
                                <label for="disco_name" class="mb-2 form-label">Disco Name</label>
                                <input type="text" name="disco_name" class="form-control @error('disco_name') is-invalid @enderror" wire:model="disco_name">
                                @error('disco_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="discount" class="mb-2 form-label">Discount %</label>
                                <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" wire:model="discount">
                                @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="image" class="mb-2 form-label">Image</label>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" wire:model="image">
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-4">
                                <img src="{{ ($image) ? $image->temporaryUrl() : $electricity->image_url }}" alt="image" width="80" class="img-fluid img-thumbnail" />
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" wire:model="status">
                                <label class="form-check-label" for="status">Status</label>
                            </div>
                            @error('status') <span class="text-danger" style="font-size: .875em">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <a href="{{ route('admin.utility.electricity') }}{{ "?vendor={$vendor->id}" }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="update">
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

@push('title')
    Utilities / Electricity / Edit - Electricity
@endpush