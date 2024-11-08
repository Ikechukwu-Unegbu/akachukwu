<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Data" link="{{ route('admin.transaction.data') }}" />
        <x-admin.page-title-item subtitle="Manage" status="true" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                        <img src="{{ $data->user->profilePicture }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $data->user->name }}</h2>
                        <h3>{{ $data->user->email }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <h5 class="card-header">
                        Data Transaction Details
                    </h5>
                    <div class="pt-2 card-body profile-overview">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Vendor</div>
                            <div class="col-lg-9 col-md-8">{{ $data->vendor->name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Service Name</div>
                            <div class="col-lg-9 col-md-8">{{ $data->plan_network }} {{ $data->data_type->name }} Data</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Phone</div>
                            <div class="col-lg-9 col-md-8">{{ $data->mobile_number }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Package</div>
                            <div class="col-lg-9 col-md-8">{{ $data->size }} - {{ $data->validity }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Amount</div>
                            <div class="col-lg-9 col-md-8">₦ {{ $data->amount }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Date</div>
                            <div class="col-lg-9 col-md-8">{{ $data->created_at->format('M d, Y. h:ia') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Transaction ID</div>
                            <div class="col-lg-9 col-md-8">{{ $data->transaction_id }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Status</div>
                            <div class="col-lg-9 col-md-8">
                                <span class="badge bg-{{ $data->status === 1 ? 'success' : ($data->status === 0 ? 'danger' : 'warning') }}">
                                {{ Str::title($data->vendor_status) }}</span>                                    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Balance Before</div>
                            <div class="col-lg-9 col-md-8">₦ {{ $data->balance_before }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Balance After</div>
                            <div class="col-lg-9 col-md-8">{{ $data->balance_after ? "₦ {$data->balance_after}" : 'N/A'
                                }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@push('title')
Transactions / Data / Manage
@endpush