<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="HR Mgt." />
        <x-admin.page-title-item subtitle="Reseller Discounts" link="{{ route('admin.hr.reseller') }}" />
        <x-admin.page-title-item subtitle="Create" status="true" />
    </x-admin.page-title>

    <section class="section">
        <form wire:submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h5 class="p-0 m-0 card-title">
                        New Reseller Discount
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mt-3 mb-2 form-group">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" wire:model="discount">
                                @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mt-3 mb-3 form-group">
                                <label for="discount_type" class="form-label">Set Discount To</label>
                                <select name="discount_type" class="form-select" id="discount_type @error('discount_type') is-invalid @enderror" wire:model="discount_type">
                                    <option>-----</option>
                                    @foreach ($discount_types as $key => $__discount_type)
                                        @if (!in_array($__discount_type, $reseller_discounts))
                                        <option value="{{ $__discount_type }}">{{ Str::title($__discount_type) }}</option> 
                                        @endif                                      
                                    @endforeach
                                </select>
                                @error('discount_type') <span class="text-danger" style="font-size: .875em">{{ $message }}</span> @enderror
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <a href="{{ route('admin.hr.reseller') }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="store">
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
Human Resource Mgt. / Reseller Discounts / Create
@endpush