<!-- Activity Log UI -->
@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <x-admin.page-title title="Activity Logs">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Activity Logs" status="true" />
    </x-admin.page-title>

    @php
$logs = collect([
    (object)[
        'id' => 1,
        'user' => (object)['name' => 'John Doe', 'email' => 'john@example.com'],
        'activity' => 'Airtime Purchase',
        'description' => 'Purchased ₦1,000 MTN airtime for 08012345678',
        'type' => 'airtime',
        'ip_address' => '197.210.54.11',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        'location' => 'Lagos, Nigeria',
        'created_at' => now()->subMinutes(5)->toDateTimeString(),
        'balance_before' => 50000,
        'balance_after' => 49000,
        'raw_request' => [
            'network' => 'MTN',
            'phone' => '08012345678',
            'amount' => 1000,
            'channel' => 'wallet'
        ],
        'raw_response' => [
            'status' => 'success',
            'message' => 'Airtime top-up successful',
        ],
        'tags' => ['airtime', 'api_call'],
        'ref_id' => 'XYZ-1234-5678'
    ],
    (object)[
        'id' => 2,
        'user' => (object)['name' => 'Jane Smith', 'email' => 'jane@example.com'],
        'activity' => 'Electricity Token Purchase',
        'description' => 'Paid ₦3,000 for Ikeja Electric prepaid token',
        'type' => 'electricity',
        'ip_address' => '105.112.45.80',
        'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0)',
        'location' => 'Abuja, Nigeria',
        'created_at' => now()->subHours(1)->toDateTimeString(),
        'balance_before' => 80000,
        'balance_after' => 77000,
        'raw_request' => [
            'disco' => 'ikeja',
            'meter_no' => '1234567890',
            'amount' => 3000,
            'channel' => 'wallet'
        ],
        'raw_response' => [
            'status' => 'success',
            'message' => 'Token generated successfully',
        ],
        'tags' => ['electricity', 'api_call'],
        'ref_id' => 'ABC-9876-4321'
    ],
    (object)[
        'id' => 3,
        'user' => (object)['name' => 'Chika Obi', 'email' => 'chika@vastel.com'],
        'activity' => 'Data Purchase',
        'description' => '₦1,200 1.5GB MTN data bundle for 08034567891',
        'type' => 'data',
        'ip_address' => '102.89.231.77',
        'user_agent' => 'Mozilla/5.0 (Linux; Android 11)',
        'location' => 'Enugu, Nigeria',
        'created_at' => now()->subMinutes(30)->toDateTimeString(),
        'balance_before' => 6000,
        'balance_after' => 4800,
        'raw_request' => [
            'network' => 'MTN',
            'phone' => '08034567891',
            'bundle' => '1.5GB',
            'amount' => 1200
        ],
        'raw_response' => [
            'status' => 'success',
            'message' => 'Data delivered successfully',
        ],
        'tags' => ['data', 'mobile'],
        'ref_id' => 'DATA-3456'
    ],
    (object)[
        'id' => 4,
        'user' => (object)['name' => 'Ibrahim Lawal', 'email' => 'ibrahim.l@vastel.com'],
        'activity' => 'DSTV Subscription',
        'description' => 'Paid ₦9,500 for Compact Plus (DSTV) - 1000123456',
        'type' => 'cable_tv',
        'ip_address' => '154.66.32.99',
        'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
        'location' => 'Kano, Nigeria',
        'created_at' => now()->subHours(2)->toDateTimeString(),
        'balance_before' => 20000,
        'balance_after' => 10500,
        'raw_request' => [
            'provider' => 'DSTV',
            'smart_card' => '1000123456',
            'package' => 'Compact Plus',
            'amount' => 9500
        ],
        'raw_response' => [
            'status' => 'success',
            'message' => 'Subscription activated'
        ],
        'tags' => ['tv', 'dstv', 'entertainment'],
        'ref_id' => 'TV-1111-3333'
    ],
    (object)[
        'id' => 5,
        'user' => (object)['name' => 'Adaeze Mba', 'email' => 'adaeze.mba@vastel.com'],
        'activity' => 'Wallet Transfer',
        'description' => 'Transferred ₦5,000 to Samson Eze - 08076543210',
        'type' => 'money_transfer',
        'ip_address' => '196.20.12.45',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0)',
        'location' => 'Benin, Nigeria',
        'created_at' => now()->subMinutes(10)->toDateTimeString(),
        'balance_before' => 15000,
        'balance_after' => 10000,
        'raw_request' => [
            'recipient' => '08076543210',
            'amount' => 5000,
            'channel' => 'wallet'
        ],
        'raw_response' => [
            'status' => 'success',
            'message' => 'Transfer successful'
        ],
        'tags' => ['transfer', 'wallet'],
        'ref_id' => 'TRF-5081'
    ],
    (object)[
        'id' => 6,
        'user' => (object)['name' => 'Samuel Okeke', 'email' => 'sam.okeke@vastel.com'],
        'activity' => 'Savings Top-Up',
        'description' => 'Saved ₦20,000 to "Emergency Fund"',
        'type' => 'savings',
        'ip_address' => '41.220.75.21',
        'user_agent' => 'Mozilla/5.0 (iPad; CPU OS 14_0)',
        'location' => 'Port Harcourt, Nigeria',
        'created_at' => now()->subDays(1)->toDateTimeString(),
        'balance_before' => 45000,
        'balance_after' => 25000,
        'raw_request' => [
            'amount' => 20000,
            'goal' => 'Emergency Fund'
        ],
        'raw_response' => [
            'status' => 'success',
            'message' => 'Savings added successfully'
        ],
        'tags' => ['savings'],
        'ref_id' => 'SAVE-2001'
    ]
]);
@endphp

    <!-- Filters -->
    <div class="card shadow-sm py-8 mb-4" style="padding-top:1rem; padding-bottom:1rem;">
        <div class="card-body">
            <form class="row g-3 py-8">
                <div class="col-md-3">
                    <label for="activityType" class="form-label">Activity Type</label>
                    <select class="form-select" id="activityType">
                        <option value="">All</option>
                        <option value="login">Login</option>
                        <option value="transfer">Money Transfer</option>
                        <option value="airtime">Airtime</option>
                        <option value="withdrawal">Withdrawal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="username" class="form-label">Username / Email</label>
                    <input type="text" class="form-control" id="username" placeholder="Search user...">
                </div>
                <div class="col-md-3">
                    <label for="dateRange" class="form-label">Date Range</label>
                    <input type="text" class="form-control" id="dateRange" placeholder="YYYY-MM-DD - YYYY-MM-DD">
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Log Table -->
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Activity</th>
                        <th>Bal B4/After</th>
                        <th>Status</th>
                        <!-- <th>Actor</th> -->
                        <th>Location</th>
                        <th>Device</th>
                        <th>Tags</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([1, 2, 3, 4, 5, 6] as $i)
                    <tr>
                        <td>{{ now()->subMinutes($i*15)->format('Y-m-d H:i') }}</td>
                        <td>
                            John Doe<br>
                            <small>john@example.com</small>
                        </td>
                        <td>
                            Money Transfer<br>
                            <small>Ref: TXN2025{{ $i }}</small>
                        </td>
                        <td>
                            ₦100,000 ➝ <span class="text-danger">₦75,000</span>
                        </td>
                        <td>
                            <span class="badge bg-success">Success</span>
                        </td>
                        <!-- <td>
                            <span class="badge bg-info text-dark">User</span>
                        </td> -->
                        <td>
                            Lagos, NG<br>
                            <small>MTN NG</small>
                        </td>
                        <td>
                            Chrome on Windows
                        </td>
                        <td>
                            <span class="badge bg-secondary">money_transfer</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#logModal{{ $i }}">Details</button>
                        </td>
                    </tr>

                    <!-- Log Modal -->
                    <div class="modal fade" id="logModal{{ $i }}" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Log Details - TXN2025{{ $i }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>User:</strong> John Doe (john@example.com)</p>
                                    <p><strong>Activity:</strong> Money Transfer to 0123456789 (₦25,000)</p>
                                    <p><strong>Balance Before:</strong> ₦100,000</p>
                                    <p><strong>Balance After:</strong> ₦75,000</p>
                                    <p><strong>Timestamp:</strong> {{ now()->subMinutes($i*15) }}</p>
                                    <p><strong>IP Address:</strong> 105.112.34.{{ $i }}</p>
                                    <p><strong>Location:</strong> Lagos, Nigeria</p>
                                    <p><strong>Device Info:</strong> Chrome 120 / Windows 10</p>
                                    <p><strong>Request Payload:</strong></p>
                                    <pre class="bg-light p-2">{
    "amount": 25000,
    "recipient": "0123456789",
    "channel": "bank_transfer"
}</pre>
                                    <p><strong>Response:</strong></p>
                                    <pre class="bg-light p-2">{
    "status": "success",
    "message": "Transfer completed"
}</pre>
                                    <p><strong>Tags:</strong> money_transfer, api_call</p>
                                    <p><strong>Raw Ref ID:</strong> 7F9B-2C19-43C7</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
               {{-- {{ $logs->links() ?? 'Pagination Placeholder' }}--}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('title')
Activity Logs
@endpush
