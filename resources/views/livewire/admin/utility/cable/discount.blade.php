<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Cable" />
        <x-admin.page-title-item subtitle="Discounts" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header m-1 p-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">{{ $vendor->name }} Vendor <br> <small>Set Discounts for cable</small></h5>
                    
                    <div><a href="{{ route('admin.utility.cable') }}?vendor={{ $vendor->id }}"
                            class="btn btn-sm btn-primary"><i class="bx bx-arrow-back"></i> Back</a></div>
                </div>
            </div>
            <form wire:submit='update'>
                <div class="card-body">
                    <div class="row mt-4">
                        <div class="col-md-8 col-lg-8 col-12">
                            @foreach ($cables as $__cable)
                                <div class="row mb-3">
                                    <label for="discount-{{ $__cable->id }}"
                                        class="col-sm-2 col-form-label">{{ $__cable->cable_name }}</label>
                                    <div class="col-sm-10">
                                        <input type="text" wire:model="discounts.{{ $__cable->id }}" class="form-control @error("discounts.{$__cable->id}") is-invalid @enderror" placeholder="Enter Discount" id="discount-{{ $__cable->id }}">
                                        @error("discounts.{$__cable->id}") <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-right">
                        <a href="{{ route('admin.utility.cable') }}?vendor={{ $vendor->id }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="update">
                            <i class="bx bx-x-circle"></i> 
                            Cancel
                        </a>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary btn-md">
                            <div wire:loading.remove wire:target="update">
                                Update
                            </div>

                            <div wire:loading wire:target="update">  
                                <i class="bx bx-loader-circle bx-spin"></i>  Updating...
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@push('title')
    Utilities / Cable / Discounts
@endpush