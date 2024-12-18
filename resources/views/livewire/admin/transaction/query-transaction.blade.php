<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="Query Vendor" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">Query Transaction Status From Vendors</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage"
                    wireSearchAction="wire:model.live=search" />
            </div>
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="['SN', 'Customer', 'Amount', 'Type', 'Date', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <th scope="row">{{ $loop->index+$transactions->firstItem() }}</th>
                                <td>{{ $transaction->user_name }}</td>
                                <td>₦{{ $transaction->amount }}</td>
                                <td>{{ Str::title($transaction->type) }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y. h:ia') }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status === 1 ? 'success' : ($transaction->status === 0 ? 'danger' : 'warning') }}">
                                        {{ Str::title($transaction->vendor_status) }}
                                    </span>
                                </td>
                                <td>
                                    <button x-on:click="$wire.queryTransaction({{ $transaction->id }}, '{{ $transaction->type }}')" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#action-query"><i class="bi bi-database"></i></button>

                                    <div wire:ignore.self class="modal fade" id="action-query" tabindex="-1" data-bs-backdrop="false">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Query Vendor</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" x-on:click="$wire.handleModal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body"> 
                                                    @if ($loader)
                                                        <div class="text-center" style="font-size: 20px">
                                                            <i class="bx bx-loader-circle bx-spin" style="font-size: 40px"></i>
                                                            <p>
                                                                Quering Transaction...
                                                                <br />
                                                                <small>Please Wait</small>
                                                            </p>
                                                        </div>
                                                    @endif
                                                    @if ($error_msg)
                                                        <h5 class="text-danger">{{ $error_msg }}</h5>
                                                    @endif
                                                    @php
                                                        $decodedResponse = json_decode(json_encode($vendorResponse), true);
                                                    @endphp
                                                    <ul>
                                                        @if (is_array($decodedResponse))
                                                        @foreach ($decodedResponse as $key => $value)
                                                            @if (!is_array($key) && !is_array($value))
                                                            <li>{{ $key }}: {{ $value }}</li>
                                                            @endif
                                                        @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-between">
                                                    @if (!$loader)
                                                    @if ($get_transaction?->vendor_status == 'pending' || $get_transaction?->vendor_status == 'processing' || $get_transaction?->vendor_status == 'failed')
                                                    <button 
                                                        type="button"
                                                        class="btn btn-warning"
                                                        x-data
                                                        x-on:click='if (confirm("Are you sure you want to refund this user?")) { $wire.handleRefund(); }'
                                                    >
                                                        Refund
                                                    </button>
                                                    @endif
                                                    <button 
                                                        type="button"
                                                        x-data
                                                        x-on:click='if (confirm("Are you sure you want to debit this user?")) { $wire.handleDebit(); }'
                                                        class="btn btn-danger" 
                                                    >
                                                        Debit
                                                    </button>
                                                    @endif
                                                    <button type="button"  x-on:click="$wire.handleModal"  class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No records available</td>
                            </tr>
                        @endforelse
                    </x-admin.table-body>
                </x-admin.table>
                <x-admin.paginate :paginate=$transactions />
            </div>
        </div>
    </section>
</div>
@push('title')
    Transactions / Query Vendors
@endpush