<div class="">
    <div class="pt-2  profile-overview">
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
            <div class="col-lg-4 col-md-4 label ">Exam Name</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->exam_name }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Quantity</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->quantity }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Amount</div>
            <div class="col-lg-8 col-md-8">₦ {{ $transactionDetails?->amount }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Date</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->created_at->format('M d, Y. h:ia') }}
            </div>
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
            <div class="col-lg-8 col-md-8">₦{{ number_format($transactionDetails?->balance_before, 2) }}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 label ">Balance After</div>
            <div class="col-lg-8 col-md-8">{{ $transactionDetails?->balance_after ? "₦" .
                number_format($transactionDetails?->balance_after, 2) : 'N/A' }}</div>
        </div>
    </div>
</div>
@if ($transactionDetails?->result_checker_pins->count())
<div class=" pt-3">
    <h5 class="">
        Generated E-PINs
    </h5>
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
                @foreach ($transactionDetails?->result_checker_pins as $pin)
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
@endif