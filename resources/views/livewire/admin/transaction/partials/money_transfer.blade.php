@php
    $statusColor = match (Str::lower($transactionDetails->transfer_status)) {
        'successful' => 'green',
        'failed' => 'red',
        'processing' => 'yellow',
        'refunded' => 'yellow',
        'pending' => 'blue',
        'n/a' => 'red',
        '' => 'red',
        'default' => 'red'
    };
    $textColor = match (Str::lower($transactionDetails->transfer_status)) {
        'successful' => 'white',
        'failed' => 'white',
        'processing' => 'black',
        'refunded' => 'black',
        'pending' => 'black',
        'n/a' => 'white',
        '' => 'white',
        'default' => 'white'
    };
@endphp

<div class="">
    <div class="pt-2 profile-overview">
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Transaction ID</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->reference_id }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Amount</div>
            <div class="col-lg-8 col-md-8">₦ {{ number_format($transactionDetails?->amount, 2) }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Date</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->created_at->format('M d, Y. h:ia') }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Type</div>
            <div class="col-lg-8 col-md-8">{{ Str::title($transactionDetails?->type) }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Status</div>
            <div class="col-lg-8 col-md-8">
                <span class="badge" style="background-color: {{ $statusColor }}; color: {{ $textColor }};">
                    {{ $transactionDetails->transfer_status ? Str::title($transactionDetails->transfer_status) : 'N/A' }}
                </span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Sender</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->sender->name }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Sender (BB)</div>
            <div class="col-lg-8 col-md-8">
                {{ $transactionDetails?->sender_balance_before ? "₦ {$transactionDetails?->sender_balance_before}" : 'N/A' }}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Sender (BA)</div>
            <div class="col-lg-8 col-md-8">
                {{ $transactionDetails?->sender_balance_after ? "₦ {$transactionDetails?->sender_balance_after}" : 'N/A' }}
            </div>
        </div>
        <hr />
        @if ($transactionDetails->type === 'internal')
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Recipient</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->receiver->name }}</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Recipient (BB)</div>
                <div class="col-lg-8 col-md-8">
                    {{ $transactionDetails?->recipient_balance_before ? "₦ {$transactionDetails?->recipient_balance_before}" : 'N/A' }}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Recipient (BA)</div>
                <div class="col-lg-8 col-md-8">
                    {{ $transactionDetails?->recipient_balance_after ? "₦ {$transactionDetails?->recipient_balance_after}" : 'N/A' }}
                </div>
            </div>
        @endif
        @if ($transactionDetails->type === 'external')
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Bank</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->bank_name }}</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Account No.</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->account_number }}</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Amount</div>
                <div class="col-lg-8 col-md-8">₦{{ number_format($transactionDetails?->amount, 2) }}</div>
            </div>
        @endif
    </div>
</div>
