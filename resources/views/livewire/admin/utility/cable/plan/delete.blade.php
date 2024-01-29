<div>
    <x-admin.page-title title="Utilities">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Utility" />
        <x-admin.page-title-item subtitle="Cable TV" link="{{ route('admin.utility.cable') }}" />
        <x-admin.page-title-item subtitle="Delete Cable Plan" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="p-0 m-0 card-title" style="">{{ $vendor->name }} ({{ $cable->cable_name }} - {{ $plan->package }})</h5>
                    <a href="{{ route('admin.utility.cable.plan', [$vendor->id, $cable->id]) }}" class="btn btn-sm btn-warning"><i class="bx bx-arrow-back"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="mt-4 text-center">
                    <h3>Are you sure?</h3>
                    <h6>You want to delete this cable plan <br /> ({{ $cable->cable_name }} - {{ $plan->package }})</h6>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <a href="{{ route('admin.utility.cable.plan', [$vendor->id, $cable->id]) }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="destroy">
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
    Utilities / Data /  Delete Cable Plan
@endpush