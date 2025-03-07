<div>
    <x-admin.page-title title="Transactions">
    <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
    <x-admin.page-title-item subtitle="Transaction" />
    <x-admin.page-title-item subtitle="Money Transfer" link="{{ route('admin.transaction.money-transfer') }}" />
    <x-admin.page-title-item subtitle="Manage" status="true" />
    </x-admin.page-title>

    <section class="section profile">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                <img src="{{ $moneyTransfer->sender->profilePicture }}" alt="Profile" class="rounded-circle">
                <h2>{{ $moneyTransfer->sender->name }}</h2>
                <h3>{{ $moneyTransfer->sender->email }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                   <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        Money Transaction Details
                    </h5>
                    <div>
                        <a href="{{ route('admin.transaction.money-transfer') }}" class="btn btn-primary"> Back</a>
                    </div>
                   </div>
                </div>
                <div class="pt-4 card-body profile-overview">
                <div class="row">
                <div class="col-lg-3 col-md-4 label ">Type</div>
                <div class="col-lg-9 col-md-8">{{ Str::title($moneyTransfer->type) }}</div>
                </div>
                <div class="row">
                <div class="col-lg-3 col-md-4 label ">Date</div>
                <div class="col-lg-9 col-md-8">{{ $moneyTransfer->created_at->format('M d, Y. h:ia') }}</div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Transaction ID</div>
                    <div class="col-lg-9 col-md-8">{{ $moneyTransfer->trx_ref ?? 'N/A' }}</div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Reference ID</div>
                    <div class="col-lg-9 col-md-8">{{ $moneyTransfer->reference_id }}</div>
                </div>
                @if ($moneyTransfer->type === 'internal')
                    <div class="row">
                        <div class="col-lg-3 col-md-3 label ">Recipient</div>
                        <div class="col-lg-9 col-md-9">{{ $moneyTransfer?->receiver->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 label ">Recipient (BB)</div>
                        <div class="col-lg-9 col-md-9">{{ $moneyTransfer?->recipient_balance_before ? "₦ {$moneyTransfer?->recipient_balance_before}" : 'N/A' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 label ">Recipient (BA)</div>
                        <div class="col-lg-9 col-md-9">{{ $moneyTransfer?->recipient_balance_after ? "₦ {$moneyTransfer?->recipient_balance_after}" : 'N/A' }}</div>
                    </div>
                @endif
                @if ($moneyTransfer->type === 'external')
                    <div class="row">
                        <div class="col-lg-3 col-md-3 label ">Bank</div>
                        <div class="col-lg-9 col-md-9">{{ $moneyTransfer?->bank_name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 label ">Account No.</div>
                        <div class="col-lg-9 col-md-9">{{ $moneyTransfer?->account_number }}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 label ">Amount</div>
                        <div class="col-lg-9 col-md-9">₦{{ number_format($moneyTransfer?->amount, 2) }}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 label ">Chanrges</div>
                        <div class="col-lg-9 col-md-9">₦{{ number_format($moneyTransfer?->charges, 2) }}</div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-3 col-md-3 label ">Balance Refund</div>
                    <div class="col-lg-9 col-md-9">₦{{ number_format($moneyTransfer?->balance_after_refund, 2) }}</div>
                </div>
                </div>
            </div>  
            </div>
        </div>
    </section>
</div>
@push('title')
Transactions / Money Transfer / Manage
@endpush