@extends('layouts.admin.app')

@section('content')
    <div>
        <x-admin.page-title title="System Health">
            <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
            <x-admin.page-title-item subtitle="System Health" status="true" />
        </x-admin.page-title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

        <div class="container mt-4">
            <div class="row g-4">
                <!-- Server Status -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">Server Status</h5>
                            <i class="bi bi-server fs-1 text-success"></i>
                            <p class="mt-2">Running Smoothly</p>
                            <span class="badge bg-success">Online</span>
                        </div>
                    </div>
                </div>

                <!-- Database Connection -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">Database Connection</h5>
                            <i class="bi bi-database fs-1 text-primary"></i>
                            <p class="mt-2">{{ $systemHealth['db_connections']['db_connections'] }}</p>
                            <span class="badge bg-primary">{{ $systemHealth['db_connections']['status'] }}</span>
                        </div>
                    </div>
                </div>
                

                <!-- Queue Workers -->
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">Queue Workers</h5>
                            <i class="bi bi-cpu fs-1 text-warning"></i>
                            <p class="mt-2">Running 3 Workers</p>
                            <span class="badge bg-warning text-dark">Operational</span>
                        </div>
                    </div>
                </div>

            
                <!-- Disk Usage -->
                @php
                    $diskUsagePct = (float) str_replace('%', '', $systemHealth['disk_usage']['usage_pct']);
                    $diskProgressBarClass = $diskUsagePct > 70 ? 'bg-danger' : 'bg-success';
                @endphp

                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Disk Usage</h5>
                            <div class="progress">
                                <div class="progress-bar {{ $diskProgressBarClass }}" role="progressbar" style="width: {{ $systemHealth['disk_usage']['usage_pct'] }}" aria-valuenow="{{ $systemHealth['disk_usage']['usage_pct'] }}" aria-valuemin="0" aria-valuemax="100">{{ $systemHealth['disk_usage']['usage_pct'] }} Used</div>
                            </div>
                            <div class="mt-2">
                                <p><strong>Total:</strong> {{ $systemHealth['disk_usage']['total'] }}</p>
                                <p><strong>Used:</strong> {{ $systemHealth['disk_usage']['used'] }}</p>
                                <p><strong>Free:</strong> {{ $systemHealth['disk_usage']['free'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- memory useage --}}
                @php
                    $usagePct = (float) str_replace('%', '', $systemHealth['memory']['usage_pct']);
                    $progressBarClass = $usagePct > 70 ? 'bg-danger' : 'bg-warning';
                @endphp
                
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Memory Usage</h5>
                            <div class="progress">
                                <div class="progress-bar {{ $progressBarClass }}" role="progressbar" style="width: {{ $systemHealth['memory']['usage_pct'] }}" aria-valuenow="{{ $systemHealth['memory']['usage_pct'] }}" aria-valuemin="0" aria-valuemax="100">{{ $systemHealth['memory']['usage_pct'] }} Used</div>
                            </div>
                            <div class="mt-2">
                                <p><strong>Total:</strong> {{ $systemHealth['memory']['total_memory'] }}</p>
                                <p><strong>Used:</strong> {{ $systemHealth['memory']['used_memory'] }}</p>
                                <p><strong>Free:</strong> {{ $systemHealth['memory']['free_memory'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h1>Services Health</h1>
                <div class="container mt-4">
                    <label for="durationSelect" class="form-label">Select Duration</label>
                    <select class="form-select" id="durationSelect">
                        <option value="">Choose...</option>
                        <option value="1h">One Hour</option>
                        <option value="1w">One Week</option>
                        <option value="1m">One Month</option>
                        <option value="3m">Three Months</option>
                        <option value="6m">Six Months</option>
                        <option value="1y">One Year</option>
                    </select>
                </div>

                {{-- api status --}}
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">API Status</h5>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">90% Uptime</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Airtime Transaction Success Rate -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Airtime Success Rate</h5>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{$airtimeSuccessRate}}%" aria-valuenow="{{$airtimeSuccessRate}}" aria-valuemin="0" aria-valuemax="100">{{$airtimeSuccessRate}}% Success</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Transaction Success Rate -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Data Success Rate</h5>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{$dataSuccessRate}}%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">{{$dataSuccessRate}}% Success</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Electricity Bills Success Rate -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Electricity Success Rate</h5>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">70% Success</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cable TV Bills Success Rate -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Cable TV Success Rate</h5>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 88%" aria-valuenow="88" aria-valuemin="0" aria-valuemax="100">88% Success</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Money Transfer Success Rate -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Money Transfer Success Rate</h5>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">60% Success</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">KYC Verification Status</h5>
                            @php
                                $percentage = $kycHealthService->percentageKycCompleted();
                                $colorClass = 'bg-danger';
                                if ($percentage > 30 && $percentage <= 60) {
                                    $colorClass = 'bg-warning';
                                } elseif ($percentage > 60) {
                                    $colorClass = 'bg-success';
                                }
                            @endphp
                            
                            <div class="progress mb-3" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped {{ $colorClass }}" 
                                     role="progressbar" 
                                     style="width: {{ $percentage }}%" 
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <span class="h6">{{ $percentage }}% Completed</span>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">0% (Not Started)</small>
                                <small class="text-muted">50% (Halfway)</small>
                                <small class="text-muted">100% (Fully Verified)</small>
                            </div>
                            
                            <div class="mt-3">
                                <div class="d-flex justify-content-between">
                                    <span>Verified Users:</span>
                                    <strong>{{ $kycHealthService->hasCompletedKyc() }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Total Users:</span>
                                    <strong>{{ $totalUsersCount }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">PalmPay Account Status</h5>
                            @php
                                $percentage = $palmPayHealthService->percentageHasPalyPayAccount();
                                $colorClass = 'bg-danger';
                                if ($percentage > 30 && $percentage <= 60) {
                                    $colorClass = 'bg-warning';
                                } elseif ($percentage > 60) {
                                    $colorClass = 'bg-success';
                                }
                            @endphp
                            
                            <div class="progress mb-3" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped {{ $colorClass }}" 
                                     role="progressbar" 
                                     style="width: {{ $percentage }}%" 
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <span class="h6">{{ $percentage }}% Completed</span>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">0% (Not Started)</small>
                                <small class="text-muted">50% (Halfway)</small>
                                <small class="text-muted">100% (Fully Verified)</small>
                            </div>
                            
                            <div class="mt-3">
                                <div class="d-flex justify-content-between">
                                    <span>Total Accounts:</span>
                                    <strong>{{ $palmPayHealthService->hasPalyPayAccount() }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Total Users:</span>
                                    <strong>{{ $totalUsersCount }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Recent Logs -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                    <div class="card-body">
    <h5 class="card-title">Recent Logs</h5>
    <ul class="list-group">
        @foreach ($transactions['airtime'] as $transaction)
            <li class="list-group-item">
                [{{ $transaction->created_at->format('H:i') }}] Airtime transaction failed ({{ $transaction->amount }} {{ $transaction->currency }})
            </li>
        @endforeach

        @foreach ($transactions['data'] as $transaction)
            <li class="list-group-item">
                [{{ $transaction->created_at->format('H:i') }}] Data transaction failed for {{ $transaction->mobile_number }}
            </li>
        @endforeach

        @if (empty($transactions['airtime']) && empty($transactions['data']))
            <li class="list-group-item text-muted">No recent failed transactions</li>
        @endif
    </ul>
</div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const selectedValue = urlParams.get('duration');
    const selectElement = document.getElementById('durationSelect');

    // Set the selected value if it exists in the URL
    if (selectedValue) {
        selectElement.value = selectedValue;
    }

    // Update the URL and refresh when selection changes
    selectElement.addEventListener('change', function() {
        const newValue = this.value;
        const url = new URL(window.location.href);
        if (newValue) {
            url.searchParams.set('duration', newValue);
        } else {
            url.searchParams.delete('duration'); // Remove param if no selection
        }
        window.location.href = url.toString(); // Reload the page with new URL
    });
});

</script>
@endsection

@push('title')
    System Health
@endpush
