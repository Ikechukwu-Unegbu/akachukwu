<div>
    <x-admin.page-title title="Transfers">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transfer" />
        <x-admin.page-title-item subtitle="Bank" link="{{ route('admin.transfer.in-app') }}" />
        <x-admin.page-title-item subtitle="Details" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0">Bank Transfer Details</h5>
                <p>Transfer Details - {{ $transfer->reference_id }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title p-2 m-0">Sender Information</h5>
            </div>
            <div class="card-body mt-2">
                <p class="mt-1 text-sm text-gray-900">
                    {{ $transfer->sender->name ?? 'N/A' }} ({{ $transfer->sender->username ?? '' }}) <br>
                    {{ $transfer->sender->email ?? '' }}
                </p>
                <p class="mt-2 text-sm">
                    <span class="text-gray-700">BB:</span>
                    ₦{{ number_format($transfer->sender_balance_before, 2) }}<br>
                    <span class="text-gray-700">BA:</span>
                    ₦{{ number_format($transfer->sender_balance_after, 2) }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title p-2 m-0">Bank Information</h5>
            </div>
            <div class="card-body mt-2">
                <p class="mt-1 text-sm text-gray-900">
                    {{ $transfer->bank_name ?? 'N/A' }}<br>
                    {{ $transfer->account_number ?? '' }}<br>
                    {{ json_decode($transfer->meta ?? '[]', true)['payeeName'] ?? 'N/A' }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title p-2 m-0">Transaction Details</h5>
            </div>
            <div class="card-body mt-2">
                <p class="mt-2 text-sm">
                    <span class="text-gray-700">Amount:</span>
                    ₦{{ number_format($transfer->amount, 2) }}<br>
                    <span class="text-gray-700">Fees:</span>
                    ₦{{ number_format($transfer->charges, 2) }}
                </p>

                <div>
                    <p class="text-sm">
                        <span class="text-gray-700">Timestamp:</span>
                        {{ $transfer->created_at->format('M d, Y h:i A') }}
                    </p>
                    <p class="text-sm">
                        <span class="text-gray-700">Status:</span>
                        <span class="badge
                                {{ $transfer->transfer_status === 'successful' ? 'bg-success' : '' }}
                                {{ $transfer->transfer_status === 'failed' ? 'bg-danger' : '' }}
                                {{ $transfer->transfer_status === 'pending' ? 'bg-warning' : '' }}
                                {{ $transfer->transfer_status === 'processing' ? 'bg-warning' : '' }}
                                {{ $transfer->transfer_status === 'refunded' ? 'bg-warning' : '' }}">
                            {{ ucfirst($transfer->transfer_status) }}
                        </span>
                    </p>
                </div>
                <div class="mt-2">
                    <p class="text-sm">
                        <span class="text-gray-700">Narration:</span>
                        {{ $transfer->narration ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        @php
            $data =  json_decode($transfer->api_response, true);
        @endphp

        @isset($data['data'])
        <div class="card">
            <div class="card-header">
                <h5 class="card-title p-2 m-0">API Response</h5>
            </div>
            <div class="card-body mt-2">
                <p><strong>Transaction Status:</strong> {{ $data['data']['message'] ?? 'Unknown' }}</p>
                <p><strong>Amount Paid:</strong> ₦{{ number_format($data['data']['amount'] / 100, 2) ?? 'N/A' }}</p>
                <p><strong>Transaction Fee:</strong> ₦{{ number_format($data['data']['fee']['fee'] / 100, 2) ?? 'N/A' }}</p>
                <p><strong>VAT on Fee:</strong> ₦{{ number_format($data['data']['fee']['vat'] / 100, 2) ?? 'N/A' }}</p>
                <p><strong>Order ID:</strong> {{ $data['data']['orderId'] ?? 'N/A' }}</p>
                <p><strong>Internal Order Number:</strong> {{ $data['data']['orderNo'] ?? 'N/A' }}</p>
                <p><strong>Transaction Reference:</strong> {{ $data['data']['sessionId'] ?? 'N/A' }}</p>
                <p><strong>Order Processing Status:</strong>
                    @if(isset($data['data']['orderStatus']))
                        @if($data['data']['orderStatus'] == 1)
                            Pending
                        @elseif($data['data']['orderStatus'] == 2)
                            Completed
                        @else
                            {{ $data['data']['orderStatus'] }}
                        @endif
                    @else
                        N/A
                    @endif
                </p>
                <p><strong>Response Message:</strong> {{ $data['data']['respMsg'] ?? 'N/A' }}</p>
                <p><strong>Response Code:</strong> {{ $data['data']['respCode'] ?? 'N/A' }}</p>
            </div>
        </div>
    @endisset

        @if ($transfer->logs && count(json_decode($transfer->logs)))
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title p-2 m-0">Logs</h4>
                </div>
                <div class="card-body mt-4">
                    <ul>
                        @foreach (json_decode($transfer->logs) as $log)
                        <li>{{ $log }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- <div class="card">
            <div class="card-header">
                <h4 class="card-title p-2 m-0">Admin Notes</h4>
            </div>
            <div class="card-body">
                <div class="p-3">
                    <textarea wire:model="adminNotes" id="adminNotes" rows="3"
                        class="form-control"></textarea>
                </div>
            </div>
        </div> --}}

        {{-- <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <div class="pt-4">
                        <button wire:click="updateTransfer('retry');"
                            class="btn btn-sm btn-primary">
                            Retry Transfer
                        </button>
                        <button wire:click="updateTransfer('reverse');"
                            class="btn btn-sm btn-warning">
                            Reverse Transfer
                        </button>
                        <button wire:click="updateTransfer('flag');"
                            class="btn btn-sm btn-danger">
                            Flag Transaction
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}
    </section>
</div>

@push('title')
    Transfer :: Bank :: Details
@endpush
