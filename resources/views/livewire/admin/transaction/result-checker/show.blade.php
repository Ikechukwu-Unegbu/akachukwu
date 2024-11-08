<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="E-PINs" link="{{ route('admin.transaction.result-checker') }}" />
        <x-admin.page-title-item subtitle="Manage" status="true" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                        <img src="{{ $resultChecker->user->profilePicture }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $resultChecker->user->name }}</h2>
                        <h3>{{ $resultChecker->user->email }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <h5 class="card-header">
                        E-PINs Transaction Details
                    </h5>
                    <div class="pt-2 card-body profile-overview">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Vendor</div>
                            <div class="col-lg-9 col-md-8">{{ $resultChecker->vendor->name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Exam Name</div>
                            <div class="col-lg-9 col-md-8">{{ $resultChecker->exam_name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Quantity</div>
                            <div class="col-lg-9 col-md-8">{{ $resultChecker->quantity }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Amount</div>
                            <div class="col-lg-9 col-md-8">₦ {{ $resultChecker->amount }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Date</div>
                            <div class="col-lg-9 col-md-8">{{ $resultChecker->created_at->format('M d, Y. h:ia') }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Transaction ID</div>
                            <div class="col-lg-9 col-md-8">{{ $resultChecker->transaction_id }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Status</div>
                            <div class="col-lg-9 col-md-8">
                                <span class="badge bg-{{ $resultChecker->status === 1 ? 'success' : ($resultChecker->status === 0 ? 'danger' : 'warning') }}">
                                    {{ Str::title($resultChecker->vendor_status) }}
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Balance Before</div>
                            <div class="col-lg-9 col-md-8">₦{{ number_format($resultChecker->balance_before, 2) }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Balance After</div>
                            <div class="col-lg-9 col-md-8">{{ $resultChecker->balance_after ? "₦" .
                                number_format($resultChecker->balance_after, 2) : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                @if ($resultChecker->result_checker_pins->count())
                <div class="card">
                    <h5 class="card-header">
                        Generated E-PINs
                    </h5>
                    <div class="pt-2 card-body profile-overview">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="3%">SN</th>
                                        <th>SERIAL</th>
                                        <th>PIN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resultChecker->result_checker_pins as $pin)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $pin->serial }}</td>
                                        <td>{{ $pin->pin }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>
@push('title')
Transactions / E-PINs / Manage
@endpush