<div>
    {{--<x-admin.page-title title="Human Resource Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="HR Mgt." />
        <x-admin.page-title-item subtitle="Users" link="{{ route('admin.hr.user') }}" />
        <x-admin.page-title-item subtitle="Wallet History" status="true" />
    </x-admin.page-title>--}}

    <section class="section profile">

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

                        @php
                            $transactionId = $wallet_transaction->transaction_id;
                            $balanceBefore = $wallet_transaction->balance_before;
                            $balanceAfter = $wallet_transaction->balance_after;
                            $status = $wallet_transaction->vendor_status;

                            if ($wallet_transaction->title === 'Intra' || $wallet_transaction->title === 'Bank') {
                                $moneyTransfer = \App\Models\MoneyTransfer::find($wallet_transaction->id);
                                $transactionId = $moneyTransfer->reference_id;

                                $status = $moneyTransfer->transfer_status;

                                if ($user->id === $moneyTransfer->user_id) {
                                    $balanceBefore = $moneyTransfer->sender_balance_before;
                                    $balanceAfter = $moneyTransfer->sender_balance_after;
                                } else {
                                    $balanceBefore = $moneyTransfer->recipient_balance_before;
                                    $balanceAfter = $moneyTransfer->recipient_balance_after;
                                }
                            }
                        @endphp

                        <tr style="font-size: 10px;">
                            <th scope="row">{{ $loop->index + $walletHistories->firstItem() }}</th>
                            <td>
                                <small>{{ $transactionId }} --- {{$wallet_transaction->title }}</small>
                            </td>
                            <td>{{ Str::title($wallet_transaction->utility) }}</td>
                            <td>₦ {{ number_format($wallet_transaction->amount, 2) }}</td>
                            <td>₦ {{ $balanceBefore ?? 'N/A' }}</td>
                            <td>₦ {{ $balanceAfter ?? 'NA' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($wallet_transaction->created_at)->format('M d, Y. h:ia') }}
                            </td>
                            <td>
                                <span class="badge
                                        {{ $status === 'successful' ? 'bg-success' : '' }}
                                        {{ $status === 'failed' ? 'bg-danger' : '' }}
                                        {{ $status === 'pending' ? 'bg-warning' : '' }}
                                        {{ $status === 'processing' ? 'bg-warning' : '' }}
                                        {{ $status === 'refunded' ? 'bg-warning' : '' }}">
                                    {{ ucfirst($status) }}
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
