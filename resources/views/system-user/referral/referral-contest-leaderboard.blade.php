@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div>
    <x-admin.page-title title="Referral Contest Leaderboard">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Referral Contests" link="{{ route('admin.referral-contest.index') }}" />
        <x-admin.page-title-item subtitle="Leaderboard" status="true" />
    </x-admin.page-title>

    <!-- Contest Statistics -->
    @if(count($sortedReferrers) > 0)
        <div class="mt-4 row">
            <div class="mb-3 col-md-3">
                <div class="text-white card bg-primary h-100">
                    <div class="p-4 card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1 fw-bold">{{ count($sortedReferrers) }}</h3>
                                <p class="mb-0 opacity-75">Total Participants</p>
                            </div>
                            <div class="align-self-center">
                                <i class="opacity-75 fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-md-3">
                <div class="text-white card bg-success h-100">
                    <div class="p-4 card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1 fw-bold">{{ collect($sortedReferrers)->sum(function($referrer) { return $referrer['total_referred']; }) }}</h3>
                                <p class="mb-0 opacity-75">Total Referrals</p>
                            </div>
                            <div class="align-self-center">
                                <i class="opacity-75 fas fa-user-plus fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-md-3">
                <div class="text-white card bg-info h-100">
                    <div class="p-4 card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1 fw-bold">{{ collect($sortedReferrers)->filter(function($referrer) { return isset($referrer['qualified_count']) && $referrer['qualified_count'] > 0; })->count() }}</h3>
                                <p class="mb-0 opacity-75">Qualified Participants</p>
                            </div>
                            <div class="align-self-center">
                                <i class="opacity-75 fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-md-3">
                <div class="text-white card bg-warning h-100">
                    <div class="p-4 card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1 fw-bold">{{ collect($sortedReferrers)->max(function($referrer) { return $referrer['total_referred']; }) }}</h3>
                                <p class="mb-0 opacity-75">Highest Referrals</p>
                            </div>
                            <div class="align-self-center">
                                <i class="opacity-75 fas fa-trophy fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="">
        <div class="shadow-sm card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Leaderboard - Contest #{{ $referralContest->id }}</h6>
                <div class="d-flex align-items-center">
                    @if(count($sortedReferrers) > 0)
                        <form method="GET" class="me-3">
                            <div class="input-group input-group-sm">
                                <label class="input-group-text" for="per_page">Show:</label>
                                <select class="form-select" name="per_page" id="per_page" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </form>
                    @endif
                    <div>
                        <span class="badge bg-info me-2">
                            <i class="fas fa-calendar"></i>
                            {{ $referralContest->start_date->format('Y-m-d H:i') }} - {{ $referralContest->end_date->format('Y-m-d H:i') }}
                        </span>
                        @if($referralContest->active === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($referralContest->active === 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-secondary">Ended</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="p-0 card-body">
                @if(count($sortedReferrers) > 0)
                    <div class="table-responsive">
                        <table class="table mb-0 align-items-center table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th width="80">Rank</th>
                                    <th>Referrer Username</th>
                                    <th>Total Referred</th>
                                    <th>Referred Users</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $index = ($paginator->currentPage() - 1) * $paginator->perPage(); @endphp
                                @foreach($paginator as $referrerUsername => $referrerData)
                                    <tr>
                                        <td>
                                            @if($index === 0)
                                                <span class="badge bg-warning fs-6">🥇 1st</span>
                                            @elseif($index === 1)
                                                <span class="badge bg-secondary fs-6">🥈 2nd</span>
                                            @elseif($index === 2)
                                                <span class="badge bg-warning fs-6" style="background-color: #cd7f32 !important;">🥉 3rd</span>
                                            @else
                                                <span class="badge bg-light text-dark">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $referrerUsername }}</strong>
                                        </td>
                                                                            <td>
                                        <span class="badge bg-primary fs-6">{{ $referrerData['total_referred'] }}</span>
                                        @if(isset($referrerData['qualified_count']) && $referrerData['qualified_count'] < $referrerData['total_referred'])
                                            <br><small class="text-muted">({{ $referrerData['qualified_count'] }} qualified)</small>
                                        @endif
                                    </td>
                                        <td>
                                            @if(count($referrerData['users']) > 0)
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#usersModal{{ $index }}_{{ $referrerUsername }}">
                                                    <i class="fas fa-users me-1"></i>
                                                    View {{ count($referrerData['users']) }} User{{ count($referrerData['users']) > 1 ? 's' : '' }}
                                                </button>
                                            @else
                                                <span class="text-muted">No referred users</span>
                                            @endif
                                        </td>
                                                                            <td>
                                        @if(isset($referrerData['qualified_count']) && $referrerData['qualified_count'] > 0)
                                            <span class="badge bg-success">Qualified</span>
                                        @else
                                            <span class="badge bg-danger">Not Qualified</span>
                                        @endif
                                    </td>
                                    </tr>
                                    @php $index++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($paginator->hasPages())
                        <div class="p-3 d-flex justify-content-between align-items-center border-top">
                            <div class="text-muted">
                                Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries
                            </div>
                            <div>
                                {{ $paginator->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="py-5 text-center">
                        <i class="mb-3 fas fa-trophy fa-3x text-muted"></i>
                        <h5 class="text-muted">No participants found</h5>
                        <p class="text-muted">No users have qualified for this referral contest yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Referred Users Modals -->
    @if(count($sortedReferrers) > 0)
        @php $modalIndex = 0; @endphp
        @foreach($paginator as $referrerUsername => $referrerData)
            @if(count($referrerData['users']) > 0)
                <div class="modal fade" id="usersModal{{ $modalIndex }}_{{ $referrerUsername }}" tabindex="-1" aria-labelledby="usersModalLabel{{ $modalIndex }}_{{ $referrerUsername }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="usersModalLabel{{ $modalIndex }}_{{ $referrerUsername }}">
                                    <i class="fas fa-users me-2"></i>
                                    Referred Users by {{ $referrerUsername }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3 row">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="text-center card-body">
                                                <h4 class="mb-1 text-primary">{{ count($referrerData['users']) }}</h4>
                                                <p class="mb-0 text-muted">Total Referred Users</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="text-center card-body">
                                                <h4 class="mb-1 text-success">{{ $referrerData['total_referred'] }}</h4>
                                                <p class="mb-0 text-muted">Total Referrals</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">#</th>
                                                <th>Username</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($referrerData['users'] as $userIndex => $username)
                                                <tr>
                                                    <td>{{ $userIndex + 1 }}</td>
                                                    <td>
                                                        <strong>{{ $username }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">Referred</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @php $modalIndex++; @endphp
        @endforeach
    @endif
</div>
@endsection

@push('title')
Referral Contest Leaderboard
@endpush
