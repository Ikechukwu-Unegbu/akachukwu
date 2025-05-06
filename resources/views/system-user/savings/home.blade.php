@extends('layouts.admin.app')

@section('content')
    <div>
        <x-admin.page-title title="System Health">
            <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
            <x-admin.page-title-item subtitle="Savings Overview" status="true" />
        </x-admin.page-title>

        {{-- Bootstrap 5 CDN --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

        <div class="container mt-4">

            {{-- Savings Summary Cards --}}
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary shadow rounded">
                        <div class="card-body">
                            <h5 class="card-title">Total Savings Users</h5>
                            <p class="card-text fs-4">1,245</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success shadow rounded">
                        <div class="card-body">
                            <h5 class="card-title">Total Saved Amount</h5>
                            <p class="card-text fs-4">₦ 45,780,000</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning shadow rounded">
                        <div class="card-body">
                            <h5 class="card-title">Average per User</h5>
                            <p class="card-text fs-4">₦ 36,756</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info shadow rounded">
                        <div class="card-body">
                            <h5 class="card-title">Active Plans</h5>
                            <p class="card-text fs-4">382</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Savings Table --}}
       {{-- Recent Savings Table with Filter --}}
<div class="card mt-5 shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Savings Activity</h5>
        <div>
            <select id="planTypeFilter" class="form-select form-select-sm w-auto">
                <option value="All">All Plans</option>
                <option value="Daily">Daily</option>
                <option value="Weekly">Weekly</option>
                <option value="Target">Target</option>
            </select>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0" id="savingsTable">
            <thead class="table-secondary">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Amount Saved</th>
                    <th>Plan Type</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach(range(1, 10) as $i)
                    @php
                        $plans = ['Daily', 'Weekly', 'Target'];
                        $plan = $plans[array_rand($plans)];
                    @endphp
                    <tr data-plan="{{ $plan }}">
                        <td>{{ $i }}</td>
                        <td>John Doe {{ $i }}</td>
                        <td>₦ {{ number_format(rand(10000, 50000)) }}</td>
                        <td>{{ $plan }}</td>
                        <td>{{ now()->subDays($i)->format('d M, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


        </div>
    </div>
@endsection

@push('title')
    Savings System Health
@endpush
