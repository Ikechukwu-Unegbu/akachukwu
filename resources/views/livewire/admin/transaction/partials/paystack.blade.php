<div class="">
    <div class="pt-2 profile-overview">
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">User</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->user->name }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Transaction ID</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->trx_ref }}</div>
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
            <div class="col-lg-4 col-md-4 label ">Status</div>
            <div class="col-lg-8 col-md-8">
                <span
                    class="badge bg-{{ $transactionDetails?->status === 1 ? 'success' : ($transactionDetails?->status === 0 ? 'danger' : 'warning') }}">
                    {{ Str::title($transactionDetails?->api_status) }}</span>
            </div>
        </div>
    </div>
</div>