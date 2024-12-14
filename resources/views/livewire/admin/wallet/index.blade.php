<div>
    <x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="HR Mgt." />
        <x-admin.page-title-item subtitle="Users" link="{{ route('admin.hr.user') }}" />
        <x-admin.page-title-item subtitle="Wallet History" status="true" />
    </x-admin.page-title>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="pt-4 card-body profile-card d-flex flex-column align-items-center">
                        <img src="{{ $user->profilePicture }}" alt="Profile" class="rounded-circle">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ $user->username }}</h3>
                        <h3>{{ $user->email }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5">
            <div class="card-header">
                <h4 class="card-title">Wallet History</h4>
            </div>
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage" wireSearchAction="wire:model.live=search"  />
            </div>
            <div class="card-body">                        
                <x-table>
                    <x-table-header :headers="['#', 'Reference', 'Type', 'Amount', 'Bal B4', 'Bal After', 'Date', 'Status']" />
                    <x-table-body>
                        @forelse ($walletHistories as $wallet_transaction)
                        <tr>
                            <th scope="row">{{ $loop->index + $walletHistories->firstItem() }}</th>
                            <td>
                                <small>{{ $wallet_transaction->transaction_id }}</small>    
                            </td>
                            <td>{{ Str::title($wallet_transaction->utility) }}</td>
                            <td>₦ {{ number_format($wallet_transaction->amount, 2) }}</td>
                            <td>₦ {{ isset($wallet_transaction->balance_before) ? $wallet_transaction->balance_before : 'NA' }}</td>
                            <td>₦ {{ isset($wallet_transaction->balance_after) ? $wallet_transaction->balance_after : 'NA' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($wallet_transaction->created_at)->format('M d, Y. h:ia') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $wallet_transaction->status === 1 ? 'success' : ($wallet_transaction->status === 0 ? 'danger' : 'warning') }}">
                                    {{ $wallet_transaction->status === 1 ? 'Successful' : ($wallet_transaction->status === 0 ? 'Failed' : 'Refunded') }}
                                    {{-- @if($wallet_transaction->type==='vastel' && $wallet_transaction->type == false)
                                        <small class="text-xs">Debit</small>
                                    @endif  --}}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">No Records Found!</td>
                        </tr>
                        @endforelse
                    </x-table-body>
                </x-table>         
                <x-paginate :paginate=$walletHistories />                   
            </div>
        </div>
    </section>
</div>
@push('title')
Human Resource Mgt. / Users / Wallet
@endpush