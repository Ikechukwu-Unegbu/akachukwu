<div>
    <x-admin.page-title title="APIs">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="APIs" />
        <x-admin.page-title-item subtitle="Payment" link="{{ route('admin.api.payment') }}" />
        <x-admin.page-title-item subtitle="Edit" status="true" />
    </x-admin.page-title>
    <section class="section">
        <form wire:submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h5 class="p-0 m-0 card-title">
                        {{ $payment->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="name" class="form-label">Payment Title</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" wire:model="name" disabled>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div> --}}
                        <div class="mt-3 col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="key" class="form-label">Secret Key</label>
                                <input type="text" name="key" class="form-control @error('key') is-invalid @enderror" wire:model="key">
                                @error('key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="public_key" class="form-label">Public Key</label>
                                <input type="text" name="public_key" class="form-control @error('public_key') is-invalid @enderror" wire:model="public_key">
                                @error('public_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        @if ($payment->name == 'Monnify')
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="contract_code" class="form-label">Contract Code</label>
                                <input type="number" name="contract_code" class="form-control @error('contract_code') is-invalid @enderror" wire:model="contract_code">
                                @error('contract_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        @endif
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
                        <a href="{{ route('admin.api.payment') }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="update">
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
Utilities / Payment / Edit
@endpush