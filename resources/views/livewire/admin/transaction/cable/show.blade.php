<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Cable" link="{{ route('admin.transaction.cable') }}" />
        <x-admin.page-title-item subtitle="Manage" status="true" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                        <img src="{{ $cable->user->profilePicture }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $cable->user->name }}</h2>
                        <h3>{{ $cable->user->email }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <h5 class="card-header">
                        Cable Transaction Details
                    </h5>
                    <div class="pt-2 card-body profile-overview">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Vendor</div>
                            <div class="col-lg-9 col-md-8">{{ $cable->vendor->name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Cable TV</div>
                            <div class="col-lg-9 col-md-8">{{ $cable->cable_name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Package</div>
                            <div class="col-lg-9 col-md-8">{{ $cable->cable_plan_name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Smart Card</div>
                            <div class="col-lg-9 col-md-8">{{ $cable->smart_card_number }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Customer Name</div>
                            <div class="col-lg-9 col-md-8">{{ $cable->customer_name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Amount</div>
                            <div class="col-lg-9 col-md-8">₦ {{ $cable->amount }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Date</div>
                            <div class="col-lg-9 col-md-8">{{ $cable->created_at->format('M d, Y. h:ia') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Transaction ID</div>
                            <div class="col-lg-9 col-md-8">{{ $cable->transaction_id }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Status</div>
                            <div class="col-lg-9 col-md-8">
                                <span class="badge bg-{{ $cable->status === 1 ? 'success' : ($cable->status === 0 ? 'danger' : 'warning') }}">
                                    {{ Str::title($cable->vendor_status) }}
                                </span>                                    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Balance Before</div>
                            <div class="col-lg-9 col-md-8">₦ {{ $cable->balance_before }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Balance After</div>
                            <div class="col-lg-9 col-md-8">{{ $cable->balance_after ? "₦ {$cable->balance_after}" : 'N/A'
                                }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@push('title')
Transactions / Cable / Manage
@endpush