<div class="">
    <div class="pt-2 profile-overview">
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Sender</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->sender->name }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Receiver</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->receiver->name }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Transaction ID</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->reference_id }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Amount</div>
            <div class="col-lg-8 col-md-8">₦ {{ $transactionDetails?->amount }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Date</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->created_at->format('M d, Y. h:ia') }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Status</div>
            <div class="col-lg-8 col-md-8">
                <span
                    class="badge bg-{{ $transactionDetails?->status === 1 ? 'success' : ($transactionDetails?->status === 0 ? 'danger' : 'warning') }}">
                    {{ Str::title($transactionDetails?->api_status) }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Balance Before</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->balance_before ? "₦ {$transactionDetails?->balance_before}" : 'N/A' }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Balance After</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->balance_after ? "₦ {$transactionDetails?->balance_after}" : 'N/A' }}</div>
        </div>
    </div>
</div>