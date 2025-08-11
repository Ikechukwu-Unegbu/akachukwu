@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="container-fluid py-4">
    <x-admin.page-title title="Referral Contests">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Referral Contests" status="true" />
    </x-admin.page-title>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">All Referral Contests</h6>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addContestModal">
                <i class="fas fa-plus"></i> New Contest
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contests as $contest)
                            <tr>
                                <td>{{ $contest->id }}</td>
                                <td>{{ $contest->start_date->format('Y-m-d H:i') }}</td>
                                <td>{{ $contest->end_date->format('Y-m-d H:i') }}</td>
                                <td>
                                    {{$contest->active}}
                                </td>
                                <td>{{ \App\Models\User::find($contest->created_by)?->name }}</td>
                                <td>{{ $contest->created_at->format('Y-m-d') }}</td>
                                <td class="text-end">
                                    <!-- Edit button -->
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editContestModal{{ $contest->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Delete form -->
                                    <form action="" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this contest?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editContestModal{{ $contest->id }}" tabindex="-1" aria-labelledby="editContestLabel{{ $contest->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{route('referral-contest.update', $contest->id)}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editContestLabel{{ $contest->id }}">Edit Referral Contest</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="start_date{{ $contest->id }}" class="form-label">Start Date</label>
                                                    <input type="datetime-local" class="form-control" id="start_date{{ $contest->id }}" name="start_date" value="{{ $contest->start_date->format('Y-m-d\TH:i') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="end_date{{ $contest->id }}" class="form-label">End Date</label>
                                                    <input type="datetime-local" class="form-control" id="end_date{{ $contest->id }}" name="end_date" value="{{ $contest->end_date->format('Y-m-d\TH:i') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="active{{ $contest->id }}" class="form-label">Active</label>
                                                    <select name="active" id="active{{ $contest->id }}" class="form-select">
                                                        <option value="pending" {{ $contest->active === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="active" {{ $contest->active === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="ended" {{ $contest->active === 'ended' ? 'selected' : '' }}>Ended</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No referral contests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add New Contest Modal -->
<div class="modal fade" id="addContestModal" tabindex="-1" aria-labelledby="addContestLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{route('admin.referral-contest.store')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addContestLabel">Add New Referral Contest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control" name="start_date" id="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control" name="end_date" id="end_date" required>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="active" class="form-label">Active</label>
                        <select name="active" id="active" class="form-select">
                            <option value="1">Yes</option>
                            <option value="0" selected>No</option>
                        </select>
                    </div> -->
                    <div class="mb-3">
    <label for="active" class="form-label">Status</label>
    <select name="active" id="active" class="form-select">
        <option value="pending" {{ old('active') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="active" {{ old('active') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="ended" {{ old('active') === 'ended' ? 'selected' : '' }}>Ended</option>
    </select>
</div>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Contest</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('title')
Referral Contests
@endpush
