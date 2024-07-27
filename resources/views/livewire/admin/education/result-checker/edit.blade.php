@push('title')
Education :: Result Checker :: Edit
@endpush

<div>
    <x-admin.page-title title="Education">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Education" />
        <x-admin.page-title-item subtitle="Result Checker" />
        <x-admin.page-title-item subtitle="Edit" status="true" />
    </x-admin.page-title>

    <section class="section">
        <form wire:submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title p-0 m-0">
                        {{ $vendor->name }} {{ $exam->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="exam_name" class="form-label">Exam Name </label>
                                <input type="text" name="exam_name" class="form-control @error('exam_name') is-invalid @enderror" wire:model="exam_name" readonly>
                                @error('exam_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-8 col-12 col-lg-8 col-xl-8">
                            <div class="mb-3 form-group">
                                <label for="amount" class="form-label">Amount <strong class="text-danger"><small>{{ $amount !== $exam->live_amount ? "(₦{$exam->live_amount})" : '' }}</small></strong></label>
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
                        <a href="{{ route('admin.education.result-checker') }}" class="btn btn-md btn-danger" wire:loading.remove wire:target="update">
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