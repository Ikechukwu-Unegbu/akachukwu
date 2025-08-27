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
                <div class="card mb-0 pb-0">
                    <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                        <img src="{{ $user->profilePicture }}" alt="Profile" class="rounded-circle">
                        <h2>Fullname: {{ $user->name }}
                            <span>
                                @if($user->blocked_by_admin)
                                    <i class="fa-solid fa-lock"></i>
                                @else
                                    <i class="fa-solid fa-check"></i>
                                @endif
                            </span>
                        </h2>

                        <h3>Username: {{ $user->username }}</h3>
                        <h3>Email: {{ $user->email }}</h3>

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
                    <button type="button"  class="btn btn-{{ $user->deleted_at ? 'success' : 'danger' }} mt-5" data-bs-toggle="modal" data-bs-target="#soft-delete">
                        {{ $user->deleted_at ? 'Undo Soft Deleted' : 'Soft Delete This User' }}
                    </button>


                    <!-- <button type="button"  class="btn btn-secondary mt-5" data-bs-toggle="modal" data-bs-target="#reset-email">
                      Send Password Reset Email
                    </button> -->


                    <!-- undo flags -->
                     <br>
                        <!-- Trigger Button -->
                    @if($user->post_no_debit == true || $user->is_blacklisted == true)
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#undoFlagsModal">
                    Undo All Flags
                    </button>
                    @endif

                    @if(!$user->post_no_debit)
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#activatePostNoDebit">
                    Activate Post No Debit
                    </button>
                    <div class="modal fade" id="activatePostNoDebit" tabindex="-1" aria-labelledby="activatePostNoDebitLabel" aria-hidden="true">
                    <div class="modal-dialog" wire:ignore.self>
                        <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header bg-danger text-white rounded-top-4">
                            <h5 class="modal-title" id="activatePostNoDebitLabel">Post No Debit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="handlePostNoDebit">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <p>Are you sure you want to activate post no debit for this user?</p>
                                <ul class="list-unstyled">
                                    <li><i class="text-danger me-1 fa fa-ban"></i> Post No Debit</li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:loading.remove wire:target="handlePostNoDebit">No, Cancel</button>
                            <button type="submit" class="btn btn-danger" wire:loading.disabled>
                                <span wire:loading.remove wire:target="handlePostNoDebit">Yes, Proceed</span>
                                <span wire:loading wire:target="handlePostNoDebit"><i class="fa fa-spinner animate"></i> Processing</span>
                            </button>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>
                    @endif

                    <!-- Modal -->
                    <div class="modal fade" id="undoFlagsModal" tabindex="-1" aria-labelledby="undoFlagsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header bg-warning text-dark rounded-top-4">
                            <h5 class="modal-title" id="undoFlagsModalLabel">Undo All User Flags</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form action="" method="POST">
                            @csrf
                            @method('PATCH') <!-- Or PUT, based on your routing -->

                            <div class="modal-body">
                            <p>Are you sure you want to remove all restrictions from this user?</p>
                            <ul class="list-unstyled">
                                @if($user->is_flagged)
                                <li><i class="text-danger me-1 fa fa-flag"></i> Currently flagged</li>
                                @endif
                                @if($user->post_no_debit)
                                <li><i class="text-danger me-1 fa fa-ban"></i> Post No Debit active</li>
                                @endif
                                @if($user->is_blacklisted)
                                <li><i class="text-danger me-1 fa fa-user-slash"></i> Blacklisted</li>
                                @endif
                            </ul>
                            </div>

                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Cancel</button>
                            <button type="button" data-bs-dismiss="modal" wire:click="dropAllFlags" class="btn btn-danger">Yes, Undo All</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>


                    <!-- end undo flags -->


                    <button type="button"  class="btn btn-secondary mt-5" data-bs-toggle="modal" data-bs-target="#reset-email">
                      Send Password Reset Email
                    </button>
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
                            <div class="col-lg-3 col-md-4 label ">Role</div>
                            <div class="col-lg-9 col-md-8">
                                @php
                                    $roleLabels = [
                                        'user' => 'User',
                                        'admin' => 'Administrator',
                                        'superadmin' => 'Super Administrator',
                                    ];
                                    $roleClasses = [
                                        'user' => 'text-secondary',
                                        'admin' => 'text-primary fw-bold',
                                        'superadmin' => 'text-danger fw-bold',
                                    ];
                                    $role = $user->role;
                                @endphp
                                <span class="{{ $roleClasses[$role] ?? 'text-dark' }}">
                                    {{ $roleLabels[$role] ?? Str::title($role) }}
                                </span>
                            </div>
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
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">KYC Name</div>
                            <div class="col-lg-9 col-md-8">{{ $user->kyc_name ?? 'N/A'}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">BVN</div>
                            <div class="col-lg-9 col-md-8">{{ $user->bvn}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">NIN</div>
                            <div class="col-lg-9 col-md-8">{{ $user->nin}}</div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Deleted At: </div>
                            <div class="col-lg-9 col-md-8">{{ $user->deleted_at}}</div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <div></div>
                            <a href="{{ route('admin.hr.user.upgrade', $user->username) }}" class="btn btn-primary">Update User</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- <div class="card mt-5"> -->

            <!-- <div class="card-body"> -->
               {{-- <x-table>
                    <x-table-header :headers="['#', 'Reference', 'Type', 'Amount','Bal B4', 'Bal After', 'Date', 'Status']" />
                    <x-table-body class="text-sm">
                        @forelse ($user->checkUserTransactionHistories(10, $user->id) as $transaction)
                            <tr style="font-size: 8px;">
                                <th scope="row">{{ $transaction?->id}}</th>
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
                </x-table>--}}
                <livewire:admin.wallet.index :user="$user"/>
            <!-- </div> -->
        <!-- </div> -->

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

    <div class="modal fade" id="soft-delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">About to Soft Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h3 class="text-center text-danger">Are you sure you want to soft delete this user?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
                        <button type="button" wire:click="softDelete"  data-bs-dismiss="modal"  class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>

    </div>


    <div class="modal fade" id="reset-email" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">About to {{ $user->deleted_at ? 'Undo Soft delete' : 'Soft Delete' }} User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 class="text-center text-danger">
                    Are you sure you want to  {{ $user->deleted_at ? 'Undo Soft delete' : 'Soft Delete' }} this user?
                </h3>
                <h5 class="modal-title" id="exampleModalLabel">About Send Password Reset Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 class="text-center text-success">Are you sure you want to send password Reset link?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button"  wire:click="sendPasswordResetLink" data-bs-dismiss="modal" class="btn btn-success">Yes, Send</button>
            </div>
            </div>
        </div>
    </div>


</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const tableId = 'userTable';
    const table = document.querySelector(`#${tableId}`);

    // Save the scroll position before navigation
    window.addEventListener('beforeunload', () => {
        const scrollPosition = table.getBoundingClientRect().top;
        sessionStorage.setItem('scrollPosition', scrollPosition);
    });

    // Restore scroll position after page reload
    const savedScrollPosition = sessionStorage.getItem('scrollPosition');
    if (savedScrollPosition) {
        window.scrollTo({
            top: parseInt(savedScrollPosition),
            behavior: 'smooth',
        });
        sessionStorage.removeItem('scrollPosition');
    }
});

</script>

@push('title')
    Human Resource Mgt. / Users
@endpush
