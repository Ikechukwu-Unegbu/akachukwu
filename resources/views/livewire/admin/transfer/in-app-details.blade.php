<div>
    <x-admin.page-title title="Transfers">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transfer" />
        <x-admin.page-title-item subtitle="In-App" link="{{ route('admin.transfer.in-app') }}" />
        <x-admin.page-title-item subtitle="Details" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0">In-App Transfer Details</h5>
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
                <h5 class="card-title p-2 m-0">Recipient Information</h5>
            </div>
            <div class="card-body mt-2">
                <p class="mt-1 text-sm text-gray-900">
                    {{ $transfer->receiver->name ?? 'N/A' }} ({{ $transfer->receiver->username ?? '' }}) <br>
                    {{ $transfer->receiver->email ?? '' }}
                </p>
                <p class="mt-2 text-sm">
                    <span class="text-gray-700">BB:</span>
                    ₦{{ number_format($transfer->recipient_balance_before, 2) }}<br>
                    <span class="text-gray-700">BA:</span>
                    ₦{{ number_format($transfer->recipient_balance_after, 2) }}
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

        <div class="card">
            <div class="card-header">
                <h4 class="card-title p-2 m-0">Admin Notes</h4>
            </div>
            <div class="card-body">
                <div class="p-3">
                    <textarea wire:model="adminNotes" id="adminNotes" rows="3"
                        class="form-control"></textarea>
                </div>
            </div>
        </div>

        <div class="card">
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
        </div>
    </section>
</div>

@push('title')
    Transfer :: InApp :: Details
@endpush
