<div>
    <x-admin.page-title title="Transactions">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Transaction" />
        <x-admin.page-title-item subtitle="All Transactions" status="true" />
    </x-admin.page-title>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="p-1 m-1 card-title">All Transactions</h5>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form method="GET" action="{{ route('admin.transaction') }}">
                    <div class="row">
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="type">Type</label>
                                <select class="form-control" wire:model="type" name="type" id="type">
                                    <option value="">All</option>
                                    @foreach ($types as $__type)
                                    <option value="{{ $__type }}">{{ Str::replace('_', ' ', Str::title($__type)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" wire:model="startDate" name="start_date"
                                    id="start_date">
                            </div>
                        </div>
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" wire:model="endDate" name="end_date"
                                    id="end_date">
                            </div>
                        </div>
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="status">Status</label>
                                <select class="form-control" wire:model="status" name="status" id="status">
                                    <option value="">All</option>
                                    @foreach ($statuses as $__status)
                                        <option value="{{ $__status }}">{{ Str::title($__status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 col-lg-3">
                            <div>
                                <label for="filter"></label>
                                <input type="submit" class="form-control btn btn-primary" value="Filter" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- @if ($status === 'pending')
            <div class="card mt-4">
                <div class="card-body p-3">
                    <button class="btn btn-primary w-25" data-bs-toggle="modal"
                        data-bs-target="#verticalycentered">Refund</button>
                    <div wire:ignore.self class="modal fade" id="verticalycentered" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Refund</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h5 class="text-center">Are you sure you want to perform this transaction refund?</h5>
                                </div>
                                <div class="modal-footer">
                                    <div class="mx-auto">
                                        <button type="button" wire:loading.remove wire:target="performRefund" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" wire:click='performRefund'>

                                            <div wire:loading.remove wire:target="performRefund">
                                                 Proceed
                                            </div>

                                            <div wire:loading wire:target="performRefund">
                                                <i class="bx bx-loader-circle bx-spin"></i>  Please wait...
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}
        <div class="card">
            <div class="card-header">
                <x-admin.perpage :perPages=$perPages wirePageAction="wire:model.live=perPage"
                    wireSearchAction="wire:model.live=search" />
            </div>
            {{-- <div class="card-header">
                <div class="row">
                    <div class="col-md-2 col-6 col-lg-3">
                        <div>
                            <label for="type">Networks</label>
                            <select class="form-control" wire:model.lazy="network" name="network" id="network">
                                <option value="">----</option>
                                @foreach ($networks as $__network)
                                <option value="{{ $__network->name }}">{{ $__network->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 col-lg-3">
                        <div>
                            <label for="type">Data Types</label>
                            <select class="form-control" wire:model.lazy="dataType" name="dataType" id="dataType">
                                <option value="">----</option>
                                @foreach ($dataTypes as $__dataType)
                                <option value="{{ $__dataType->name }}">{{ $__dataType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="card-body">
                <x-admin.table>
                    <x-admin.table-header :headers="[$status === 'pending' ? '#' : 'SN', 'Customer', 'Amount', 'Type', 'Date', 'Status', 'Action']" />
                    <x-admin.table-body>
                        @forelse ($transactions as $transaction)
                        @php
                            $isFunding = $transaction->type === 'funding';
                            $isTransfer = $transaction->plan_name === 'Transfer';
                            $isCredit = $isFunding ? true : ($transaction->vendor_status === 'refunded' ? true : false);
                            $statusColor = match (Str::lower($transaction->vendor_status)) {
                                'successful' => 'green',
                                'failed'     => 'red',
                                'processing' => 'yellow',
                                'refunded'   => 'yellow',
                                'pending'    => 'yellow',
                                'n/a'    => 'red',
                                ''    => 'red',
                                'default'    => 'red'
                            };
                            $textColor = match (Str::lower($transaction->vendor_status)) {
                                'successful' => 'white',
                                'failed'     => 'white',
                                'processing' => 'black',
                                'refunded'   => 'black',
                                'pending'    => 'black',
                                'n/a'    => 'white',
                                ''    => 'white',
                                'default'    => 'white'
                            };
                        @endphp
                            <tr>
                                <th scope="row">
                                    {{ $loop->index + $transactions->firstItem() }}
                                </th>
                                <td> <a href="{{route('admin.hr.user.show', [$transaction->user_name])}}">{{ $transaction->user_name }}</a> </td>
                                <td>₦{{ $transaction->amount }}</td>
                                <td>
                                    @if ($transaction->utility === 'money_transfer')
                                        {{ $transaction->type === 'external' ? 'Bank' : 'Intra' }} Transfer
                                    @else
                                    {{ Str::replace('_', ' ', Str::title($transaction->utility)) }}
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y. h:ia') }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $statusColor }}; color: {{ $textColor }};">
                                        {{ $transaction->vendor_status ? Str::title($transaction->vendor_status) : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <li><a x-on:click="$wire.handleTransaction({{ $transaction->id }}, '{{ $transaction->utility }}')" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#action-modal" class="dropdown-item text-success"><i class="bx bx-bullseye"></i> View</a></li>
                                            <li><a href="javascript.void(0)" x-on:click="$wire.queryTransaction({{ $transaction->id }}, '{{ $transaction->utility }}')" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#action-query"><i class="bi bi-database"></i>Query Vendor</a> </li>
                                        </ul>
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

                {{ $transactions->links() }}
                <div wire:ignore.self class="modal fade" id="action-modal" tabindex="-1" data-bs-backdrop="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $transaction_type ? Str::replace('_', ' ', Str::title($transaction_type)) . " Transaction" : '' }}</h5>
                                <button type="button" class="btn-close" x-on:click="$wire.handleModal" data-bs-dismiss="modal"aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if ($loader)
                                    <div class="text-center" style="font-size: 20px"><i class="bx bx-loader-circle bx-spin" style="font-size: 40px"></i>
                                        <p class="p-0 m-0">Gathering Information...</p>
                                        <p class="p-0 m-0"><small>Please Wait</small></p>
                                    </div>
                                @else
                                @php $viewPath = "livewire.admin.transaction.partials.{$transaction_type}"; @endphp
                                @if(view()->exists($viewPath))
                                    @include($viewPath, ['transactionDetails' => $transactionDetails])
                                @else
                                    Transaction Modal Not Found!
                                @endif
                                @endif
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" x-on:click="$wire.handleModal" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

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
                                @if (!empty($vendorResponse))
                                <button
                                    type="button"
                                    x-data
                                    x-on:click='if (confirm("Are you sure you want to debit this user?")) { $wire.handleDebit(); }'
                                    class="btn btn-danger"
                                >
                                    Debit
                                </button>
                                @endif
                                @endif
                                <button type="button"  x-on:click="$wire.handleModal"  class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@push('title')
    Transactions / All Transactions
@endpush
