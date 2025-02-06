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
                                <div class="progress-bar bg-success" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">80% Success</div>
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

                <!-- Recent Logs -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Recent Logs</h5>
                            <ul class="list-group">
                                <li class="list-group-item">[12:34] Airtime transaction completed</li>
                                <li class="list-group-item">[12:35] Data purchase successful</li>
                                <li class="list-group-item">[12:36] Money transfer failed</li>
                                <li class="list-group-item text-muted">More logs...</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('title')
    System Health
@endpush
