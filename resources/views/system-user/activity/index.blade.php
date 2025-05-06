@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <x-admin.page-title title="Activity Log">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Activity Log" status="true" />
    </x-admin.page-title>

    <!-- Filters -->
    <form class="card mb-4 shadow-sm p-3" method="GET" action="">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="activity_type" class="form-label">Activity Type</label>
                <select class="form-select" id="activity_type" name="activity_type">
                    <option value="">All</option>
                    <option value="login">Login</option>
                    <option value="airtime_purchase">Airtime Purchase</option>
                    <option value="data_purchase">Data Purchase</option>
                    <option value="transfer">Transfer</option>
                    <option value="create_savings_plan">Create Savings</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="user_query" class="form-label">User (Email/Username)</label>
                <input type="text" class="form-control" id="user_query" name="user_query" placeholder="e.g. jane@domain.com">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button class="btn btn-primary">Filter Logs</button>
            </div>
        </div>
    </form>

    <!-- Activity Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Activity Logs</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Activity</th>
                        <th>Module</th>
                        <th>Status</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Date</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['id' => 1, 'user' => 'Jane Smith', 'email' => 'jane@site.com', 'action' => 'airtime_purchase', 'module' => 'airtime', 'status' => 'success', 'ip' => '102.89.200.21', 'agent' => 'Chrome', 'date' => '2025-05-05 09:23:11', 'props' => ['amount' => 500, 'network' => 'MTN', 'phone' => '08012345678']],
                        ['id' => 2, 'user' => 'John Doe', 'email' => 'john@site.com', 'action' => 'login', 'module' => 'auth', 'status' => 'success', 'ip' => '105.211.45.1', 'agent' => 'Safari', 'date' => '2025-05-04 18:00:44', 'props' => ['location' => 'Lagos']],
                        ['id' => 3, 'user' => 'Alex Green', 'email' => 'alex@site.com', 'action' => 'transfer', 'module' => 'wallet', 'status' => 'failed', 'ip' => '102.11.25.76', 'agent' => 'Edge', 'date' => '2025-05-04 12:22:10', 'props' => ['bank' => 'GTB', 'amount' => 15000, 'reason' => 'Insufficient balance']],
                    ] as $log)
                        <tr>
                            <td>
                                <strong>{{ $log['user'] }}</strong><br>
                                <small class="text-muted">{{ $log['email'] }}</small>
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $log['action'])) }}</td>
                            <td>{{ ucfirst($log['module']) }}</td>
                            <td>
                                <span class="badge {{ $log['status'] === 'success' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($log['status']) }}
                                </span>
                            </td>
                            <td>{{ $log['ip'] }}</td>
                            <td><span class="text-muted small">{{ $log['agent'] }}</span></td>
                            <td><small>{{ $log['date'] }}</small></td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#details-{{ $log['id'] }}">
                                    View
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="details-{{ $log['id'] }}">
                            <td colspan="8" class="bg-light">
                                <strong>Properties:</strong>
                                <pre class="mb-0">{{ json_encode($log['props'], JSON_PRETTY_PRINT) }}</pre>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer text-end">
            {{-- Pagination (example) --}}
            <nav>
                <ul class="pagination justify-content-end mb-0">
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
