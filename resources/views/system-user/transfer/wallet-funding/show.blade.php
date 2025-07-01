@extends('layouts.admin.app')
@section('content')
    <x-admin.page-title title="Wallet Funding Details">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Wallet Funding" link="{{ route('admin.wallet-funding.index') }}" />
        <x-admin.page-title-item subtitle="Details" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="mb-3 card">
            <div class="p-2 m-0 card-header">
                <h5 class="p-2 m-0 card-title ">User Information</h5>
            </div>
            <div class="card-body">
                <p class="mt-3 mb-1"><strong>Username:</strong> {{ $transaction->username }}</p>
                <p class="mb-1"><strong>User ID:</strong> {{ $transaction->user_id }}</p>
            </div>
        </div>

        <div class="mb-3 card">
            <div class="card-header">
                <h5 class="p-2 m-0 card-title ">Transaction Details</h5>
            </div>
            <div class="card-body">
                <p class="mt-3 mb-1"><strong>Transaction ID:</strong> {{ $transaction->transaction_id }}</p>
                <p class="mb-1"><strong>Amount:</strong> ₦{{ number_format($transaction->amount, 2) }}</p>
                <p class="mb-1"><strong>Type:</strong> {{ ucfirst($transaction->type) }}</p>
                <p class="mb-1"><strong>Utility:</strong> {{ ucfirst($transaction->utility) }}</p>
                <p class="mb-1"><strong>Balance Before:</strong> ₦{{ $transaction->balance_before }}</p>
                <p class="mb-1"><strong>Balance After:</strong> ₦{{ $transaction->balance_after }}</p>
                <p class="mb-1"><strong>Created At:</strong> {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y h:i A') }}</p>
                <p class="mb-1"><strong>Status:</strong>  <span class="badge
                    {{ $transaction->vendor_status === 'successful' ? 'bg-success' : '' }}
                    {{ $transaction->vendor_status === 'failed' ? 'bg-danger' : '' }}
                    {{ $transaction->vendor_status === 'pending' ? 'bg-warning' : '' }}
                    {{ $transaction->vendor_status === 'processing' ? 'bg-warning' : '' }}
                    {{ $transaction->vendor_status === 'refunded' ? 'bg-warning' : '' }}">
                    {{ ucfirst($transaction->vendor_status) }}
                </span></p>
            </div>
        </div>

        <div class="mb-3 card">
            <div class="p-2 m-0 card-header">
                <h5 class="p-2 m-0 card-title ">Gateway Information</h5>
            </div>
            <div class="card-body">
                <p class="mt-3 mb-1"><strong>Gateway:</strong> {{ ucfirst($transaction->vendor) }}</p>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
    </section>
@endsection
