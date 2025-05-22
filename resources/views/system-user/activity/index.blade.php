<!-- Activity Log UI -->
@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <x-admin.page-title title="Activity Logs">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Activity Logs" status="true" />
    </x-admin.page-title>

    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Moment.js -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

<!-- Daterangepicker CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>




    <!-- Filters -->
    <div class="card shadow-sm py-8 mb-4" style="padding-top:1rem; padding-bottom:1rem;">
        <div class="card-body">
        <form class="row g-3 py-4">
    <div class="col-12 col-md-3">
        <label for="activityType" class="form-label">Activity Type</label>
        <select class="form-select" id="activityType" name="activity_type">
            <option value="">All</option>
            @foreach ($constants as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12 col-md-3">
        <label for="username" class="form-label">Username / Email</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Search user...">
    </div>

    <div class="col-12 col-md-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" class="form-control" id="start_date" name="start_date">
    </div>

    <div class="col-12 col-md-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date">
    </div>

    <div class="col-12 col-md-3 align-self-end">
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
                        <th>ID</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Activity</th>
                        <!-- <th>Bal B4/After</th> -->
                        <!-- <th>Status</th> -->
                        <!-- <th>Actor</th> -->
                        <th>Location</th>
                        <th>Device</th>
                        <th>Tags</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                    <tr>
                        <td>#{{$activity->id}}</td>
                        <td><small>{{ $activity->created_at->format('d M Y')  }}</small></td>
                        <td>
                            <a href="">{{$activity->actor->name}}</a>
                            <!-- <small>john@example.com</small> -->
                        </td>
                        <td>
                            {{$activity->type}}<br>
                            <small>{{$activity->activity}}</small>
                        </td>
                        <!-- <td>
                            ₦100,000 ➝ <span class="text-danger">₦75,000</span>
                        </td>
                        <td>
                            <span class="badge bg-success">Success</span>
                        </td> -->
                        <!-- <td>
                            <span class="badge bg-info text-dark">User</span>
                        </td> -->
                        <td>
                            Lagos, NG<br>
                            <small>MTN NG</small>
                        </td>
                        <td>
                           {{$activity->getUserAgentSummaryAttribute()}}
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{$activity->getTagsListAttribute()}}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#logModal{{ $activity->id }}">Details</button>
                        </td>
                    </tr>

                    <!-- Log Modal -->
                    <div class="modal fade" id="logModal{{ $activity->id }}" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                            a        <h5 class="modal-title">Log Details </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>User:</strong> {{$activity->actor->name}}</p>
                                    <p><strong>Activity:</strong> {{$activity->type}} | {{$activity->activity}}</p>
                                    <!-- <p><strong>Balance Before:</strong> ₦100,000</p>
                                    <p><strong>Balance After:</strong> ₦75,000</p> -->
                                    <p><strong>Timestamp:</strong> {{ $activity->created_at->format('j M Y') }}</p>
                                    <p><strong>IP Address:</strong>{{ $activity->ip_address }}</p>
                                    <p><strong>Location:</strong> {{$activity->getFormattedLocationAttribute()}}</p>
                                    <p><strong>Device Info:</strong> {{$activity->getUserAgentSummaryAttribute()}}</p>
                                    <!-- <p><strong>Request Payload:</strong></p>
                                    <pre class="bg-light p-2">
                                    {
                                        "amount": 25000,
                                        "recipient": "0123456789",
                                        "channel": "bank_transfer"
                                    }</pre>
                                    <p><strong>Response:</strong></p>
                                    <pre class="bg-light p-2">
                                    {
                                        "status": "success",
                                        "message": "Transfer completed"
                                    }</pre> -->
                                    <p><strong>Tags:</strong> {{$activity->getTagsListAttribute()}}</p>
                                    <p><strong>Raw Ref ID:</strong> 7F9B-2C19-43C7</p>
                                    <p>
                                    

                                    @php
    $diffs = $activity->getModelDifferences();
@endphp

@if(count($diffs) === 1 && isset($diffs['status']))
    <div class="alert alert-info">
        {{ $diffs['status'] }}
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach($diffs as $attribute => $change)
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">{{ str_replace('_', ' ', $attribute) }}</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Old:</strong>
                                {{ is_array($change['old']) ? json_encode($change['old']) : (is_null($change['old']) ? 'NULL' : $change['old']) }}
                            </li>
                            <li class="list-group-item">
                                <strong>New:</strong>
                                {{ is_array($change['new']) ? json_encode($change['new']) : (is_null($change['new']) ? 'NULL' : $change['new']) }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

                                        


                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
               {{ $activities->links() ?? 'Pagination Placeholder' }}
            </div>
        </div>
    </div>
</div>


@endsection

@push('title')
Activity Logs
@endpush
