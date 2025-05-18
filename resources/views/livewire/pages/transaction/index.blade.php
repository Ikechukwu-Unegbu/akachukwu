<div>
    <div class="w-full bg-white p-8 flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Transactions</h2>
        <div class="flex space-x-4">
            <!-- Categories Dropdown -->
            <div class="relative">
                <select wire:model.live="service"
                    class="appearance-none border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-vastel_blue">
                    <option value="">All Categories</option>
                    @foreach ($services as $__service)
                        <option value="{{ $__service }}">{{ Str::title($__service) }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>

            <!-- Date Selector -->
            <div class="relative">
                <select wire:model.live="selectedDate"
                    class="appearance-none border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-vastel_blue">
                    @foreach ($months as $month)
                        <option value="{{ $year }}-{{ \Carbon\Carbon::parse($month)->format('m') }}">
                            {{ $month }} {{ $year }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>
    <!-- Transactions List -->
    <div class="bg-white rounded-lg shadow p-4 space-y-4">
        <!-- Transaction Item -->
        {{-- @forelse ($transactions as $transaction)
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <!-- Icon -->
                    <i class="fas {{ $transaction->icon }} text-blue-500 text-xl"></i>
                    <div>
                        <p class="font-semibold">{{ $transaction->title }} ({{ Str::upper($transaction->utility) }})</p>

                        @if ($transaction->type !== 'funding')
                            @php
                                $moneyTransfer = \App\Models\MoneyTransfer::find($transaction->id);
                                $subscribedTo = \App\Models\User::find($transaction->subscribed_to)?->name
                                    ?? ($moneyTransfer && $moneyTransfer->account_number
                                        ? $moneyTransfer->account_number . " (" . $moneyTransfer->bank_name . ")"
                                        : $transaction->subscribed_to);
                            @endphp
                            <p class="text-sm text-gray-500">
                                {{ Str::title($transaction->type) }}: {{ $subscribedTo }}
                            </p>
                        @endif


                        @if ($transaction->type === 'funding')
                            <a class="text-vastel_blue text-sm">{{ $transaction->transaction_id }}</a>
                        @endif
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y. h:iA') }}</p>
                        @if ($transaction->type !== 'funding' && $transaction->plan_name !== 'Transfer')
                            <a href="{{ route("user.transaction.". Str::lower($transaction->utility) .".receipt", $transaction->transaction_id) }}" class="text-vastel_blue text-sm">View Receipt</a>
                            <i class="fa-solid fa-grip-lines-vertical text-vastel_blue"></i>
                            <a href="{{ route("api.response", [Str::lower($transaction->utility), $transaction->transaction_id]) }}" class="text-vastel_blue text-sm">Api Response</a>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-{{ $transaction->status === 1 ? 'green' : ($transaction->status === 0 ? 'red' : 'yellow') }}-600">₦{{ number_format($transaction->amount, 2) }}</p>
                    <span class="text-xs text-{{ $transaction->status === 1 ? 'green' : ($transaction->status === 0 ? 'red' : 'yellow') }}-600 bg-{{ $transaction->status === 1 ? 'green' : ($transaction->status === 0 ? 'red' : 'yellow') }}-100 px-2 py-1 rounded-full">
                    {{ Str::title($transaction->vendor_status) }}
                    </span>
                </div>
            </div>

            @if (!$loop->last)
                <hr>
            @endif

        @empty
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                    <div>
                        <h4 class="text-red-500">No Records Found!</h4>
                    </div>
                </div>
            </div>
        @endforelse --}}

        @forelse ($transactions as $transaction)
            @php
                $isFunding = $transaction->type === 'funding';
                $isTransfer = $transaction->plan_name === 'Transfer';
                $isCredit = $isFunding ? true : false;
                $statusColor = match (Str::lower($transaction->vendor_status)) {
                    'successful' => 'green',
                    'failed'     => 'red',
                    'processing' => 'yellow',
                    'refunded'   => 'yellow',
                    'pending'    => 'yellow',
                    'n/a'    => 'red',
                    'default'    => 'red'
                };
            @endphp

            <div class="flex justify-between items-center p-4 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="flex items-center space-x-4">
                    <!-- Dynamic Icon with different colors based on type -->
                    <div class="flex-shrink-0">
                        @if ($isFunding)
                            <i class="fas fa-wallet text-green-500 text-xl bg-green-50 p-3 rounded-full"></i>
                        @elseif($isTransfer)
                            <i class="fas fa-exchange-alt text-blue-500 text-xl bg-blue-50 p-3 rounded-full"></i>
                        @else
                            <i class="fas {{ $transaction->icon }} text-blue-500 text-xl bg-blue-50 p-3 rounded-full"></i>
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center flex-wrap gap-2">
                            <p class="font-semibold text-gray-800">{{ Str::title($transaction->title) }}</p>
                            {{-- <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                {{ Str::upper($transaction->utility) }}
                            </span> --}}
                        </div>

                        @if ($transaction->type !== 'funding')
                            @php
                                $moneyTransfer = \App\Models\MoneyTransfer::find($transaction->id);
                                // $subscribedTo =
                                //     \App\Models\User::find($transaction->subscribed_to)?->name ??
                                //     ($moneyTransfer && $moneyTransfer->account_number
                                //         ? $moneyTransfer->account_number . ' (' . $moneyTransfer->bank_name . ')'
                                //         : $transaction->subscribed_to);
                            @endphp
                            <p class="text-sm text-gray-500 mt-1">
                                <p class="text-sm text-vastel_blue mt-1">{{ $moneyTransfer->reference_id }}</p>
                            </p>
                        @endif

                        @if ($isFunding)
                            <p class="text-sm text-vastel_blue mt-1">{{ $transaction->transaction_id }}</p>
                        @endif

                        <p class="text-xs text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y · h:i A') }}
                        </p>

                        @if (!$isFunding && !$isTransfer)
                            <div class="flex items-center gap-3 mt-2">
                                <a href="{{ route('user.transaction.' . Str::lower($transaction->utility) . '.receipt', $transaction->transaction_id) }}"
                                    class="text-vastel_blue text-sm hover:underline flex items-center gap-1">
                                    <i class="fas fa-receipt text-sm"></i> Receipt
                                </a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('api.response', [Str::lower($transaction->utility), $transaction->transaction_id]) }}"
                                    class="text-vastel_blue text-sm hover:underline flex items-center gap-1">
                                    <i class="fas fa-code text-sm"></i> API Response
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-right">
                    <p class="font-bold text-{{ $isCredit ? 'green' : 'red' }}-600">
                        {{ $isCredit ? '+' : '-' }}₦{{ number_format(abs($transaction->amount), 2) }}
                    </p>
                    <span
                        class="text-xs text-{{ $statusColor }}-600 bg-{{ $statusColor }}-100 px-2 py-1 rounded-full mt-1 inline-block">
                        {{ Str::title($transaction->vendor_status) }}
                    </span>
                </div>
            </div>

            @if (!$loop->last)
                <hr class="border-gray-100 mx-4">
            @endif

        @empty
            <div class="text-center py-8">
                <i class="fas fa-exchange-alt text-gray-300 text-4xl mb-3"></i>
                <h4 class="text-gray-500 font-semibold">No Transactions Found</h4>
                <p class="text-gray-400 text-sm mt-1">Your transaction history will appear here</p>
            </div>
        @endforelse
        <div class="py-5">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
