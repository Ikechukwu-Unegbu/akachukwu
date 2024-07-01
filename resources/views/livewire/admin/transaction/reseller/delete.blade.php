<div>
    <x-admin.page-title title="Reseller Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transactions" />
        <x-admin.page-title-item subtitle="Reseller Discounts" link="{{ route('admin.transaction.reseller') }}" />
        <x-admin.page-title-item subtitle="Delete" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="p-0 m-0 card-title" style="">Delete Reseller Discount ({{ Str::title($reseller->type) }})</h5>
                    <a href="{{ route('admin.transaction.reseller') }}" class="btn btn-sm btn-warning"><i class="bx bx-arrow-back"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="mt-4 text-center">
                    <h3>Are you sure?</h3>
                    <h6>You want to delete this Reseller Discount <br /> <span class="text-danger">({{ Str::title($reseller->type) }})</span></h6>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <a href="{{ route('admin.transaction.reseller') }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="destroy">
                        <i class="bx bx-x-circle"></i> 
                        Cancel
                    </a>
                    <button type="submit" wire:click.prevent="destroy" class="btn btn-primary btn-md">
                        <div wire:loading.remove wire:target="destroy">
                            <i class="bx bx-trash"></i>  Delete
                        </div>

                        <div wire:loading wire:target="destroy">
                            <i class="bx bx-loader-circle bx-spin"></i>  Deleting...
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>
@push('title')
Transactions / Reseller Discounts / Delete
@endpush