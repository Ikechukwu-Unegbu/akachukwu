@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <x-admin.page-title title="Withdrawal Requests">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Withdrawal Requests" status="true" />
    </x-admin.page-title>

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">Pending Withdrawal Requests</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Requested On</th>
                        <th>Status</th>
                        <th>Source</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['id' => 1, 'user' => 'John Doe', 'amount' => '₦50,000', 'date' => '2025-05-01', 'status' => 'Pending', 'source' => 'Flexible', 'savings' => ['type' => 'Flexible', 'balance' => '₦200,000', 'start_date' => '2025-01-01']],
                        ['id' => 2, 'user' => 'Jane Smith', 'amount' => '₦100,000', 'date' => '2025-04-30', 'status' => 'Pending', 'source' => 'Fixed 90-Day', 'savings' => ['type' => 'Fixed', 'balance' => '₦300,000', 'start_date' => '2025-03-01']],
                    ] as $request)
                        <tr>
                            <td>{{ $request['user'] }}</td>
                            <td>{{ $request['amount'] }}</td>
                            <td>{{ $request['date'] }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $request['status'] }}</span></td>
                            <td>{{ $request['source'] }}</td>
                            <td>
                                <button class="btn btn-sm btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#approveModal"
                                    data-id="{{ $request['id'] }}"
                                    data-user="{{ $request['user'] }}"
                                    data-amount="{{ $request['amount'] }}"
                                    data-type="{{ $request['savings']['type'] }}"
                                    data-balance="{{ $request['savings']['balance'] }}"
                                    data-start="{{ $request['savings']['start_date'] }}"
                                >Approve</button>

                                <button class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#declineModal" 
                                    data-id="{{ $request['id'] }}" 
                                    data-user="{{ $request['user'] }}"
                                >
                                    Decline
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="declineForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Decline Withdrawal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="declineRequestId">
                    <div class="mb-3">
                        <label for="declineUser" class="form-label">User</label>
                        <input type="text" class="form-control" id="declineUser" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="declineReason" class="form-label">Internal Note (not visible to user)</label>
                        <textarea id="declineReason" class="form-control" rows="3" placeholder="E.g., Declined because user attempted early withdrawal before 90-day lock period."></textarea>
                    </div>
                    <div class="alert alert-warning small">
                        This note is for internal use only.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Confirm Decline</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="approveForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Withdrawal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="approveRequestId">
                    <div class="mb-3">
                        <label for="approveUser" class="form-label">User</label>
                        <input type="text" class="form-control" id="approveUser" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Withdrawal Amount</label>
                        <input type="text" class="form-control" id="approveAmount" disabled>
                    </div>
                    <hr>
                    <h6>User Savings Details</h6>
                    <ul class="list-group small">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Type</span><strong id="savingsType"></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Balance</span><strong id="savingsBalance"></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Start Date</span><strong id="savingsStart"></strong>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Confirm Approval</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Decline modal
    const declineModal = document.getElementById('declineModal');
    declineModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const requestId = button.getAttribute('data-id');
        const user = button.getAttribute('data-user');
        document.getElementById('declineRequestId').value = requestId;
        document.getElementById('declineUser').value = user;
        document.getElementById('declineReason').value = '';
    });

    document.getElementById('declineForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const requestId = document.getElementById('declineRequestId').value;
        const note = document.getElementById('declineReason').value;
        alert(`Withdrawal request #${requestId} declined with note:\n${note}`);
        bootstrap.Modal.getInstance(declineModal).hide();
    });

    // Approve modal
    const approveModal = document.getElementById('approveModal');
    approveModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('approveRequestId').value = button.getAttribute('data-id');
        document.getElementById('approveUser').value = button.getAttribute('data-user');
        document.getElementById('approveAmount').value = button.getAttribute('data-amount');
        document.getElementById('savingsType').textContent = button.getAttribute('data-type');
        document.getElementById('savingsBalance').textContent = button.getAttribute('data-balance');
        document.getElementById('savingsStart').textContent = button.getAttribute('data-start');
    });

    document.getElementById('approveForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const requestId = document.getElementById('approveRequestId').value;
        alert(`Withdrawal request #${requestId} approved.`);
        bootstrap.Modal.getInstance(approveModal).hide();
    });
</script>
@endsection

@push('title')
Handle Withdrawal Requests
@endpush
