@extends('layouts.admin.app')
@section('content')
    <x-admin.page-title title="Transfers">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Scheduled Transactions" />
    </x-admin.page-title>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="p-1 m-1 card-title">Scheduled Transactions Mgt.</h5>
                    <div>
                        <a href="{{ route('admin.scheduled.index') }}" class="btn btn-sm btn-secondary">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
         <div class="card">
            <div class="card-body">
                <div class="processing-indicator" id="processingIndicator">
                    <div class="processing-content">
                        <div class="spinner"></div>
                        <p id="processingText">Processing your request...</p>
                    </div>
                </div>
                <div class="text-center">
                    <div class="pt-4">
                        <!-- Retry Button (only show if transaction can be retried) -->
                        <button class="btn btn-warning btn-action btn-sm" data-action="retry"
                            data-id="{{ $transaction->id }}">
                            <i class="fas fa-redo"></i> Retry
                        </button>

                        <!-- Cancel Button -->
                        <button class="btn btn-danger btn-action btn-sm" data-action="cancel"
                            data-id="{{ $transaction->id }}">
                            <i class="fas fa-times"></i> Cancel
                        </button>

                        <!-- Notify User Button -->
                        <button class="btn btn-info btn-action btn-sm" data-action="notify"
                            data-id="{{ $transaction->id }}">
                            <i class="fas fa-envelope"></i> Notify User
                        </button>

                        <!-- Add Note Button with Modal -->
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#noteModal"
                            data-id="{{ $transaction->id }}">
                            <i class="fas fa-edit"></i> Add Note
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <div class="card">
            <div class="card-body">
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6 class="card-title">Schedule Information</h6>
                        <table class="table">
                            <tr>
                                <th width="30%">Transaction ID</th>
                                <td>{{ $transaction->uuid }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{ ucfirst($transaction->type) }}</td>
                            </tr>
                            <tr>
                                <th>Frequency</th>
                                <td>{{ ucfirst($transaction->frequency) }}</td>
                            </tr>
                            <tr>
                                <th width="30%">Next Run At</th>
                                <td>{{ $transaction->next_run_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Last Run At</th>
                                <td>{{ $transaction->last_run_at ? $transaction->last_run_at->format('Y-m-d H:i') : 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $transaction->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span
                                        class="badge
                                                                                    {{ $transaction->status === 'completed' ? 'bg-success' : '' }}
                                                                                    {{ $transaction->status === 'failed' ? 'bg-danger' : '' }}
                                                                                    {{ $transaction->status === 'pending' ? 'bg-warning' : '' }}
                                                                                    {{ $transaction->status === 'processing' ? 'bg-warning' : '' }}
                                                                                    {{ $transaction->status === 'disabled' ? 'bg-danger' : '' }}">
                                        {{ ucfirst($transaction->status) }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h4 class="card-title">Schedule Logs</h4>
                        @if ($transaction->logs)
                            <div id="logsContainer" style="max-height: 50vh; overflow: auto;">
                                <h6 class="mt-4">Logs</h6>
                                <pre class="bg-light p-3">{{ json_encode($transaction->logs, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>


            </div>
            @if ($transaction->vendor_status == 'failed')
                <div class="card-footer">
                    <div class="mt-4">
                        <a href="{{ route('admin.scheduled.retry', [$productType, $transaction->id]) }}"
                            class="btn btn-warning">
                            Retry Transaction
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title p-2 m-0">User Information</h5>
            </div>
            <div class="card-body mt-2">
                <p class="mt-1 text-sm text-gray-900">
                    {{ $transaction->user->name ?? 'N/A' }} ({{ $transaction->user->username ?? '' }}) <br>
                    {{ $transaction->user->email ?? '' }}
                </p>
                <p class="mt-2 text-sm">
                    <span class="text-gray-700">Wallet Balance:</span>
                    ₦{{ number_format($transaction->user->account_balance, 2) }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title">{{ ucfirst($transaction->type) }} Transactions</h4>
                    <form action="{{ route('admin.scheduled.show', $transaction->uuid) }}" method="GET" class="d-inline">
                        <select name="perPage" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
                            @foreach ([50, 100, 200] as $perPage)
                                <option value="{{ $perPage }}"
                                    {{ request('perPage', 50) == $perPage ? 'selected' : '' }}>
                                    {{ $perPage }} per page
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <x-admin.table>
                        <x-admin.table-header :headers="[
                            'Trx. ID',
                            'Phone No.',
                            'Network',
                            'Vendor',
                            'Data Plan',
                            'Amount',
                            'Bal. B4',
                            'Bal. After',
                            'After Refund',
                            'Discount',
                            'Date',
                            'Status',
                        ]" />
                        <x-admin.table-body>
                            @forelse ($latestTransactions as $__transaction)
                                <tr>
                                    <td>{{ $__transaction->transaction_id }}</td>
                                    <td>{{ $__transaction->mobile_number }}</td>
                                    <td>{{ $__transaction->plan_network }}</td>
                                    <td>{{ $__transaction->vendor->name }}</td>
                                    <td>{{ $__transaction->size }}</td>
                                    <td>₦{{ $__transaction->amount }}</td>
                                    <td>₦{{ $__transaction->balance_before }}</td>
                                    <td>₦{{ $__transaction->balance_after }}</td>
                                    <td>₦{{ $__transaction->balance_after_refund }}</td>
                                    <td>%{{ $__transaction->discount }}</td>
                                    <td>{{ $__transaction->created_at->format('M d, Y. h:ia') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $__transaction->status === 1 ? 'success' : ($__transaction->status === 0 ? 'danger' : 'warning') }}">
                                            {{ Str::title($__transaction->vendor_status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No records available</td>
                                </tr>
                            @endforelse
                        </x-admin.table-body>
                    </x-admin.table>

                    <div class="mt-3">
                        {{ $latestTransactions->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Internal Notes</h4>
                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#noteModal" data-id="{{ $transaction->id }}">
                    <i class="fas fa-plus"></i> Add Note
                </button>
            </div>
            <div class="card-body mt-3">
                @if ($transaction->notes && count($transaction->notes) > 0)
                    <div class="timeline">
                        @foreach (array_reverse($transaction->notes) as $note)
                            <div class="timeline-item">
                                <div class="timeline-item-marker">
                                    <div
                                        class="timeline-item-marker-indicator bg-{{ $transaction->getNoteColor($note['type']) }}">
                                    </div>
                                </div>
                                <div class="timeline-item-content">
                                    <div class="d-flex justify-content-between">
                                        <span class="font-weight-bold">{{ $transaction->getNoteType($note['type']) }}</span>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($note['timestamp'])->format('M j, Y g:i A') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $note['content'] }}</p>
                                    <small class="text-muted">Added by:
                                        {{ $transaction->getAdminName($note['admin_id']) }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        No internal notes have been added yet.
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="processing-indicator" id="processingIndicator">
                    <div class="processing-content">
                        <div class="spinner"></div>
                        <p id="processingText">Processing your request...</p>
                    </div>
                </div>
                <div class="text-center">
                    <div class="pt-4">
                        <!-- Retry Button (only show if transaction can be retried) -->
                        <button class="btn btn-warning btn-action btn-sm" data-action="retry"
                            data-id="{{ $transaction->id }}">
                            <i class="fas fa-redo"></i> Retry
                        </button>

                        <!-- Cancel Button -->
                        <button class="btn btn-danger btn-action btn-sm" data-action="cancel"
                            data-id="{{ $transaction->id }}">
                            <i class="fas fa-times"></i> Cancel
                        </button>

                        <!-- Notify User Button -->
                        <button class="btn btn-info btn-action btn-sm" data-action="notify"
                            data-id="{{ $transaction->id }}">
                            <i class="fas fa-envelope"></i> Notify User
                        </button>

                        <!-- Add Note Button with Modal -->
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#noteModal"
                            data-id="{{ $transaction->id }}">
                            <i class="fas fa-edit"></i> Add Note
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Note Modal -->
        <div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noteModalLabel">Add Internal Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea id="noteContent" class="form-control" rows="5" placeholder="Enter note content..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-note-submit" data-action="note">Save
                            Note</button>
                    </div>
                </div>
            </div>
        </div>

    </section>

    @push('title')
        Scheduled Transactions
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                function showProcessing(message = 'Processing your request...') {
                    $('#processingText').text(message);
                    $('#processingIndicator').fadeIn();
                }

                function hideProcessing() {
                    $('#processingIndicator').fadeOut();
                }
                // Handle action buttons
                $('.btn-action').click(function() {
                    const action = $(this).data('action');
                    const id = $(this).data('id');
                    let actionText = '';

                    switch (action) {
                        case 'retry':
                            actionText = 'Retrying transaction';
                            break;
                        case 'cancel':
                            actionText = 'Cancelling transaction';
                            break;
                        case 'notify':
                            actionText = 'Notifying user';
                            break;
                        default:
                            actionText = 'Processing';
                    }

                    showProcessing(actionText);

                    if (action === 'note') return;

                    if (action === 'cancel' || action === 'notify' || action === 'retry') {
                        if (!confirm(`Are you sure you want to ${action} this transaction?`)) {
                            hideProcessing();
                            return;
                        }
                    }

                    $.ajax({
                        url: "{{ route('admin.scheduled.update', '') }}/" + id,
                        method: 'PUT',
                        data: {
                            action: action,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            toastr.success(response.success);
                            // Optional: reload page or update UI
                            setTimeout(() => location.reload(), 1000);
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON.error || 'Operation failed');
                        },
                        complete: function() {
                            hideProcessing();
                        }
                    });
                });

                // Handle note submission
                $('#noteModal').on('show.bs.modal', function(event) {
                    const button = $(event.relatedTarget);
                    const id = button.data('id');
                    const modal = $(this);

                    modal.find('.btn-note-submit').data('id', id);
                });

                $('.btn-note-submit').click(function() {
                    const id = $(this).data('id');
                    const content = $('#noteContent').val();
                    showProcessing('Saving note...');
                    if (!content.trim()) {
                        toastr.warning('Please enter note content');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('admin.scheduled.update', '') }}/" + id,
                        method: 'PUT',
                        data: {
                            action: 'note',
                            note: content,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            toastr.success(response.success);
                            $('#noteModal').modal('hide');
                            $('#noteContent').val('');
                            setTimeout(() => location.reload(), 1000);
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON.error || 'Failed to save note');
                        },
                        complete: function() {
                            hideProcessing();
                        }
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const logsContainer = document.getElementById('logsContainer');
                if (logsContainer) {
                    logsContainer.scrollTop = logsContainer.scrollHeight;
                    logsContainer.scrollTo({
                        top: logsContainer.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .processing-indicator {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 9999;
            }

            .processing-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
            }

            .spinner {
                width: 40px;
                height: 40px;
                margin: 0 auto 15px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #3498db;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
        <style>
            .timeline {
                position: relative;
                padding-left: 1rem;
            }

            .timeline-item {
                position: relative;
                padding-bottom: 1.5rem;
            }

            .timeline-item:last-child {
                padding-bottom: 0;
            }

            .timeline-item-marker {
                position: absolute;
                left: -1.25rem;
                top: 0;
            }

            .timeline-item-marker-indicator {
                width: 12px;
                height: 12px;
                border-radius: 100%;
                background: #adb5bd;
            }

            .timeline-item-content {
                padding: 0.5rem 0.75rem;
                background-color: #f8f9fa;
                border-radius: 0.25rem;
                margin-bottom: 1rem;
            }

            .bg-note {
                background-color: #6c757d;
            }

            .bg-retry {
                background-color: #ffc107;
            }

            .bg-cancel {
                background-color: #dc3545;
            }

            .bg-notification {
                background-color: #17a2b8;
            }
        </style>
    @endpush
@endsection
