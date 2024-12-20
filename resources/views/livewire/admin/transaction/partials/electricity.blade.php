<div class="col-xl-8">
    <div class="">
        <div class="pt-2 profile-overview">
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">User</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->user->name }}</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Vendor</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->vendor->name }}</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Transaction ID</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->transaction_id }}</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Disco Name</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->disco_name }}</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Meter No.</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->meter_number }} - ({{ $transactionDetails?->meter_type_name }})</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Customer Name</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->customer_name }}</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Customer Address</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->customer_address }}</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Customer Phone</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->customer_mobile_number }}</div>
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
                    <span class="badge bg-{{ $transactionDetails?->status === 1 ? 'success' : ($transactionDetails?->status === 0 ? 'danger' : 'warning') }}">
                        {{ Str::title($transactionDetails?->vendor_status) }}
                    </span>                                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Balance Before</div>
                <div class="col-lg-8 col-md-8">₦ {{ $transactionDetails?->balance_before }}</div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 label ">Balance After</div>
                <div class="col-lg-8 col-md-8">{{ $transactionDetails?->balance_after ? "₦ {$transactionDetails?->balance_after}" : 'N/A'
                    }}</div>
            </div>
        </div>
    </div>

</div>