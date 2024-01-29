<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Cable TV" link="{{ route('admin.utility.cable') }}" />
        <x-admin.page-title-item subtitle="New Cable Plan" status="true" />
    </x-admin.page-title>

    <section class="section">
        <form wire:submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h5 class="p-0 m-0 card-title">
                        {{ $vendor->name }} ({{ $cable->cable_name }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mt-3 mb-3 form-group">
                                <label for="api_id" class="form-label">API ID</label>
                                <input type="number" name="api_id" class="form-control @error('api_id') is-invalid @enderror" wire:model="api_id">
                                @error('api_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="package" class="form-label">Package</label>
                                <input type="text" name="package" class="form-control @error('package') is-invalid @enderror" wire:model="package">
                                @error('package') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" wire:model="amount">
                                @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <a href="{{ route('admin.utility.cable.plan', [$vendor->id, $cable->id]) }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="store">
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
Utilities / Cable TV / New - Cable Plan
@endpush