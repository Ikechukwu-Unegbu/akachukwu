<div>
    <x-admin.page-title title="APIs">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="APIs" />
        <x-admin.page-title-item subtitle="Payment" link="{{ route('admin.api.payment') }}" />
        <x-admin.page-title-item subtitle="Show" status="true" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="p-0 m-0 card-title" style="">{{ $payment->name }} Gateway</h5>
                    <a href="{{ route('admin.api.payment') }}" class="btn btn-sm btn-primary"><i class="bx bx-arrow-back"></i> Back</a>
                </div>
            </div>
            <div class="pt-4 card-body profile-overview">                
                <div class="row">
                    <div class="col-lg-3 col-md-4 label">Secret Key</div>
                    <div class="col-lg-9 col-md-8">{{ $payment->key }}</div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4 label">Status</div>
                    <div class="col-lg-9 col-md-8"><span class="badge bg-{{ $payment->status ? 'success' : 'danger' }}">{{ $payment->status ? 'Active' : 'Not-Active' }}</span></div>
                </div>
              </div>
        </div>
    </section>
</div>

@push('title')
    API / Payment / Show
@endpush
