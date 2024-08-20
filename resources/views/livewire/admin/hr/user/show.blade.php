<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="HR Mgt." />
        <x-admin.page-title-item subtitle="Users" link="{{ route('admin.hr.user') }}" />
        <x-admin.page-title-item subtitle="Show" status="true" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                        <img src="{{ $user->profilePicture }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ $user->username }}</h3>
                        <h3>{{ $user->email }}</h3>
                    </div>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Block this user
                    </button>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <h5 class="card-header">
                        User Details
                    </h5>
                    <div class="pt-2 card-body profile-overview"> 
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Level</div>
                            <div class="col-lg-9 col-md-8">{{ Str::title($user->user_level) }}</div>
                        </div>                       
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Address</div>
                            <div class="col-lg-9 col-md-8">{{ $user->address ? $user->address : 'N/A'  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Mobile</div>
                            <div class="col-lg-9 col-md-8">{{ $user->mobile ? $user->mobile : 'N/A'  }}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Gender</div>
                            <div class="col-lg-9 col-md-8">{{ $user->gender ? $user->gender : 'N/A'  }}</div>
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
                    <x-table-header :headers="['#', 'Reference', 'Gateway', 'Amount', 'Date', 'Status']" />
                    <x-table-body>
                        @forelse ($walletHistories as $wallet_transaction)
                        <tr>
                            <th scope="row">{{ $loop->index + 1 }}</th>
                            <td>
                                <small>{{ $wallet_transaction->reference_id }}</small>    
                            </td>
                            <td>{{ Str::title($wallet_transaction->gateway_type) }}</td>
                            <td>₦ {{ number_format($wallet_transaction->amount, 2) }}</td>
                            <td>
                                @php $createdAt = \Carbon\Carbon::parse($wallet_transaction->created_at); @endphp
                                <small>{{ $createdAt->format('M d, Y. h:ia') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $wallet_transaction->status ? 'success' : 'danger' }}">
                                    {{ $wallet_transaction->status ? 'Successful' : 'Failed' }}
                                </span>
                            </td>
                        </tr>
                        @if ($loop->last)
                            <tr>
                                <td colspan="7">
                                    <a href="{{ route('admin.wallet.history', $user->username) }}" class="btn btn-sm btn-primary">Show More</a>
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
    </section>
<!-- starting of modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- end of modal -->
</div>


@push('title')
Human Resource Mgt. / Users
@endpush