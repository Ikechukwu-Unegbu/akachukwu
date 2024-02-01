<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Electricity" link="{{ route('admin.transaction.data') }}" />
        <x-admin.page-title-item subtitle="Manage" status="true" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                        <img src="{{ $electricity->user->profilePicture }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $electricity->user->name }}</h2>
                        <h3>{{ $electricity->user->email }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <h5 class="card-header">
                        Electricity Transaction Details
                    </h5>
                    <div class="pt-2 card-body profile-overview">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Vendor</div>
                            <div class="col-lg-9 col-md-8">{{ $electricity->vendor->name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Disco Name</div>
                            <div class="col-lg-9 col-md-8">{{ $electricity->disco_name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Meter No.</div>
                            <div class="col-lg-9 col-md-8">{{ $electricity->meter_number }} - ({{ $electricity->meter_type_name }})</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Customer Name</div>
                            <div class="col-lg-9 col-md-8">{{ $electricity->customer_name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Customer Address</div>
                            <div class="col-lg-9 col-md-8">{{ $electricity->customer_address }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Customer Phone</div>
                            <div class="col-lg-9 col-md-8">{{ $electricity->customer_mobile_number }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Amount</div>
                            <div class="col-lg-9 col-md-8">₦ {{ $electricity->amount }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Date</div>
                            <div class="col-lg-9 col-md-8">{{ $electricity->created_at->format('M d, Y. h:ia') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Transaction ID</div>
                            <div class="col-lg-9 col-md-8">{{ $electricity->transaction_id }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Status</div>
                            <div class="col-lg-9 col-md-8"><span
                                    class="badge bg-{{ $electricity->status ? 'success' : 'danger' }}">{{ $electricity->status ?
                                    'Successful' : 'Failed' }}</span></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Balance Before</div>
                            <div class="col-lg-9 col-md-8">₦ {{ $electricity->balance_before }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Balance After</div>
                            <div class="col-lg-9 col-md-8">{{ $electricity->balance_after ? "₦ {$electricity->balance_after}" : 'N/A'
                                }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@push('title')
Transactions / Electricity / Manage
@endpush