@extends('layouts.admin.app')
@section('content')

    <x-admin.page-title title="Transfers">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transfer" />
        <x-admin.page-title-item subtitle="In-App" link="{{ route('admin.transfer.bank') }}" />
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

        @isset($apiResponse['data'])
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title p-2 m-0">API Response</h5>
                </div>
                <div class="card-body mt-2">
                    <p><strong>Transaction Status:</strong> {{ $apiResponse['data']['message'] ?? 'Unknown' }}</p>
                    <p><strong>Amount Paid:</strong> ₦{{ number_format($apiResponse['data']['amount'] / 100, 2) ?? 'N/A' }}
                    </p>
                    <p><strong>Transaction Fee:</strong>
                        ₦{{ number_format($apiResponse['data']['fee']['fee'] / 100, 2) ?? 'N/A' }}</p>
                    <p><strong>VAT on Fee:</strong>
                        ₦{{ number_format($apiResponse['data']['fee']['vat'] / 100, 2) ?? 'N/A' }}</p>
                    <p><strong>Order ID:</strong> {{ $apiResponse['data']['orderId'] ?? 'N/A' }}</p>
                    <p><strong>Internal Order Number:</strong> {{ $apiResponse['data']['orderNo'] ?? 'N/A' }}</p>
                    <p><strong>Transaction Reference:</strong> {{ $apiResponse['data']['sessionId'] ?? 'N/A' }}</p>
                    <p><strong>Order Processing Status:</strong>
                        @if(isset($apiResponse['data']['orderStatus']))
                            @if($apiResponse['data']['orderStatus'] == 1)
                                Pending
                            @elseif($apiResponse['data']['orderStatus'] == 2)
                                Completed
                            @else
                                {{ $apiResponse['data']['orderStatus'] }}
                            @endif
                        @else
                            N/A
                        @endif
                    </p>
                    <p><strong>Response Message:</strong> {{ $apiResponse['data']['respMsg'] ?? 'N/A' }}</p>
                    <p><strong>Response Code:</strong> {{ $apiResponse['data']['respCode'] ?? 'N/A' }}</p>
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

        <div class="card">
            <div class="card-header">
                <h4 class="card-title p-2 m-0">Admin Notes</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.transfer.bank.details.update', $transfer) }}" method="POST">
                    @csrf
                    <div class="p-3">
                        <textarea name="adminNotes" id="adminNotes" rows="3"
                            class="form-control">{{ old('adminNotes', $transfer->note) }}</textarea>
                    </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <div class="pt-4">
                        {{-- <button type="submit" name="action" value="retry" class="btn btn-sm btn-primary">
                            Retry Transfer
                        </button> --}}
                        @if ($transfer->transfer_status !== 'refunded')
                        <button type="submit" name="action" value="reverse" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to reverse this transfer? This action cannot be undone.');">
                            Reverse Transfer
                        </button>
                        @endif
                        <button type="submit" name="action" value="flag" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to flag this transaction? This action will mark the transaction for further review.');">
                            Flag Transaction
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>

    </section>
@endsection

@push('title')
    Transfer :: Bank :: Details
@endpush
