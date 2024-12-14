<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="HR Mgt." />
        <x-admin.page-title-item subtitle="Users" link="{{ route('admin.hr.user') }}" />
        <x-admin.page-title-item subtitle="Show" status="true" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                        <img src="{{ $user->profilePicture }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $user->name }} <span>@if($user->blocked_by_admin ==true) <i class="fa-solid fa-lock"></i> @else <i class="fa-solid fa-check"></i>@endif</span></h2>
                        <h3>{{ $user->username }}</h3>
                        <h3>{{ $user->email }}</h3>
                    </div>
                    @if($user->blocked_by_admin == false)
                    <button type="button"  class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#block_modal">
                        Block this user
                    </button>
                    @else 
                    <button type="button"  class="btn btn-success" data-bs-toggle="modal" data-bs-target="#unblock_modal">
                        Unblock User
                    </button>
                    @endif 
                </div>
                <livewire:component.user-account-numbers-card :user="$user"/>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4>User Details</h4>
                            @can('impersonate')
                            <div class="filter">
                                <a class="btn btn-primary btn-sm" href="#" data-bs-toggle="dropdown">Action</a>                                
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li><a href="#" class="dropdown-item text-success" data-bs-toggle="modal"
                                            data-bs-target="#impersonateUserModal">Login as {{ $user->name }}</a></li>
                                </ul>
                            </div>
                            @endcan
                        </div>
                    </div>
                    <div class="pt-2 card-body profile-overview">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Level</div>
                            <div class="col-lg-9 col-md-8">{{ Str::title($user->user_level) }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Address</div>
                            <div class="col-lg-9 col-md-8">{{ $user->address ? $user->address : 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Mobile</div>
                            <div class="col-lg-9 col-md-8">{{ $user->mobile ? $user->mobile : 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Gender</div>
                            <div class="col-lg-9 col-md-8">{{ $user->gender ? $user->gender : 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Account Balance</div>
                            <div class="col-lg-9 col-md-8">₦ {{ $user->account_balance }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Level</div>
                            <div class="col-lg-9 col-md-8">{{ Str::title($user->user_level) }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Joined On</div>
                            <div class="col-lg-9 col-md-8">{{ $user->created_at->format('d M, Y') }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card mt-5">
            <div class="card-header">
                <h4 class="card-title">Wallet History</h4>
            </div>
            <div class="card-body">
                <x-table>
                    <x-table-header :headers="['#', 'Reference', 'Type', 'Amount','Bal B4', 'Bal After', 'Date', 'Status']" />
                    <x-table-body class="text-sm">
                        @forelse ($user->checkUserTransactionHistories(10, $user->id) as $transaction)
                            <tr style="font-size: 10px;">
                                <th scope="row">{{ $loop->index + 1 }}</th>
                                <td>
                                    <small>{{ $transaction->transaction_id }}</small>
                                </td>
                                <td>{{ Str::title($transaction->utility) }}</td>
                                <td>₦ {{ number_format($transaction->amount, 2) }}</td>
                                <td>₦ {{ isset($transaction->balance_before) ? $transaction->balance_before : 'NA' }}</td>
                                <td>₦ {{ isset($transaction->balance_after) ? $transaction->balance_after : 'NA' }}</td>

                              
                                <td>
                                    <small>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y. h:ia') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status === 1 ? 'success' : ($transaction->status === 0 ? 'danger' : 'warning') }}">
                                        {{ Str::title($transaction->vendor_status) }}
                                    </span>
                                </td>
                            </tr>
                            @if ($loop->last)
                                <tr>
                                    <td colspan="7">
                                        <a href="{{ route('admin.wallet.history', $user->username) }}"
                                            class="btn btn-sm btn-primary">Show More</a>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7">No Records Found!</td>
                            </tr>
                        @endforelse
                    </x-table-body>
                </x-table>
            </div>
        </div>

        <div class="modal fade" id="impersonateUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Impersonate {{ $user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('impersonate.start', $user) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="text-center">
                                <h5>Are you sure?</h5>
                                <p>You want to Impersonate <span class="text-danger">{{ $user->name }}</span></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<!-- starting of modal -->
    <div class="modal fade" id="block_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">About to Block User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 class="text-center text-danger">Are you sure you want to block this user?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
                <button type="button" wire:click="blockUser"  data-bs-dismiss="modal"  class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="unblock_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">About to Unblock User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 class="text-center text-success">Are you sure about unblocking this user?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button"  wire:click="unBlockUser" data-bs-dismiss="modal" class="btn btn-success">Yes, Unblock</button>
            </div>
            </div>
        </div>
    </div>
<!-- end of modal -->
</div>


@push('title')
    Human Resource Mgt. / Users
@endpush
