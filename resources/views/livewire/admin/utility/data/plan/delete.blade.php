@push('title')
    Utilities / Data /  Delete Data Plans
@endpush

<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Data" link="{{ route('admin.utility.data') }}" />
        <x-admin.page-title-item subtitle="Delete Plan" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title p-0 m-0" style="">{{ $vendor->name }} ({{ $network->name }} - {{ $type->name }} - {{ $plan->size }} Plan)</h5>
                    <a href="{{ route('admin.utility.data.plan', [$vendor->id, $network->id, $type->id]) }}" class="btn btn-sm btn-warning"><i class="bx bx-arrow-back"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center mt-4">
                    <h3>Are you sure?</h3>
                    <h6>You want to delete this data plan <br /> ({{ $type->name }} - {{ $plan->size }})</h6>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <a href="{{ route('admin.utility.data.plan', [$vendor->id, $network->id, $type->id]) }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="destroy">
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
