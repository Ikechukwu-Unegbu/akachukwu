@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <x-admin.page-title title="Plan Settings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Savings Plan Settings" status="true" />
    </x-admin.page-title>

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">Available Savings Plans</h5>
            <button class="btn btn-outline-primary mt-2 mt-md-0" onclick="notifyPartner()">Notify Partner for Adjustments</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Plan Name</th>
                        <th>Description</th>
                        <th>Interest Rate</th>
                        <th>Min. Amount</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['id' => 1, 'name' => 'Flexible', 'desc' => 'Withdraw anytime', 'rate' => '5%', 'min' => '₦5,000', 'duration' => 'No fixed term', 'status' => 'Active'],
                        ['id' => 2, 'name' => 'Fixed 3-Month', 'desc' => 'Locked for 3 months', 'rate' => '10%', 'min' => '₦10,000', 'duration' => '3 Months', 'status' => 'Active'],
                        ['id' => 3, 'name' => 'Fixed 6-Month', 'desc' => 'Locked for 6 months', 'rate' => '15%', 'min' => '₦20,000', 'duration' => '6 Months', 'status' => 'Paused'],
                    ] as $plan)
                        <tr>
                            <td>{{ $plan['name'] }}</td>
                            <td>{{ $plan['desc'] }}</td>
                            <td>{{ $plan['rate'] }}</td>
                            <td>{{ $plan['min'] }}</td>
                            <td>{{ $plan['duration'] }}</td>
                            <td>
                                <span class="badge bg-{{ $plan['status'] === 'Active' ? 'success' : 'warning' }}">{{ $plan['status'] }}</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editPlanModal"
                                    data-id="{{ $plan['id'] }}"
                                    data-name="{{ $plan['name'] }}"
                                    data-desc="{{ $plan['desc'] }}"
                                    data-rate="{{ $plan['rate'] }}"
                                    data-min="{{ $plan['min'] }}"
                                    data-duration="{{ $plan['duration'] }}"
                                    data-status="{{ $plan['status'] }}">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Plan Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="planName" class="form-label">Plan Name</label>
                            <input type="text" id="planName" class="form-control" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="planStatus" class="form-label">Status</label>
                            <input type="text" id="planStatus" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="planDesc" class="form-label">Description</label>
                        <input type="text" id="planDesc" class="form-control" disabled>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="planRate" class="form-label">Interest Rate</label>
                            <input type="text" id="planRate" class="form-control" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="planMin" class="form-label">Min. Amount</label>
                            <input type="text" id="planMin" class="form-control" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="planDuration" class="form-label">Duration</label>
                            <input type="text" id="planDuration" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="text-muted">
                        Contact your partner admin to request edits to this plan.
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function notifyPartner() {
        alert("Partner has been notified about plan adjustment needs.");
    }

    const editModal = document.getElementById('editPlanModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('planName').value = button.getAttribute('data-name');
        document.getElementById('planDesc').value = button.getAttribute('data-desc');
        document.getElementById('planRate').value = button.getAttribute('data-rate');
        document.getElementById('planMin').value = button.getAttribute('data-min');
        document.getElementById('planDuration').value = button.getAttribute('data-duration');
        document.getElementById('planStatus').value = button.getAttribute('data-status');
    });
</script>
@endsection

@push('title')
Manage Plan Settings
@endpush
