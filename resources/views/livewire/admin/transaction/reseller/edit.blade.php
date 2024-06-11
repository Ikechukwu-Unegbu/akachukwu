<div>
    <x-admin.page-title title="Reseller Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transactions" />
        <x-admin.page-title-item subtitle="Reseller Discounts" link="{{ route('admin.transaction.reseller') }}" />
        <x-admin.page-title-item subtitle="Edit" status="true" />
    </x-admin.page-title>

    <section class="section">
        <form wire:submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h5 class="p-0 m-0 card-title">
                        Edit Reseller Discount ({{ Str::title($reseller->type) }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mt-3 mb-4 form-group">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" wire:model="discount">
                                @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <a href="{{ route('admin.transaction.reseller') }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="update">
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
Transactions / Reseller Discounts / Edit
@endpush