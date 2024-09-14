<div>
    <div class="w-full bg-white p-8 flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Transactions</h2>
        <div class="flex space-x-4">
            <!-- Categories Dropdown -->
            <div class="relative">
                <select wire:model.live="service" class="appearance-none border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">All Categories</option>
                    @foreach ($services as $__service)
                        <option value="{{ $__service }}">{{ Str::title($__service) }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>

            <!-- Date Selector -->
            <div class="relative">
                <select wire:model.live="selectedDate" class="appearance-none border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    @foreach ($months as $month)
                    <option value="{{ $year }}-{{ \Carbon\Carbon::parse($month)->format('m') }}">{{ $month }} {{ $year }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>
    <!-- Transactions List -->
    <div class="bg-white rounded-lg shadow p-4 space-y-4">
        <!-- Transaction Item -->
        @forelse ($transactions as $transaction)
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <!-- Icon -->
                    <i class="fas {{ $transaction->icon }} text-blue-500 text-xl"></i>
                    <div>
                        <p class="font-semibold">{{ $transaction->title }} ({{ Str::upper($transaction->type !== 'funding' ? $transaction->plan_name : $transaction->utility) }})</p>
                        @if ($transaction->type !== 'funding')
                        <p class="text-sm text-gray-500">{{ $transaction->type }}: {{ $transaction->subscribed_to }}</p>
                        @endif
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y. h:iA') }}</p>
                        @if ($transaction->type === 'funding')
                            <a class="text-blue-600 text-sm">{{ $transaction->transaction_id }}</a>
                        @else
                            <a href="{{ route("user.transaction.{$transaction->utility}.receipt", $transaction->transaction_id) }}" class="text-blue-600 text-sm">View Receipt</a>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-{{ $transaction->status ? 'green' : 'red' }}-600">₦{{ number_format($transaction->amount, 2) }}</p>
                    <span class="text-xs text-{{ $transaction->status ? 'green' : 'red' }}-600 bg-{{ $transaction->status ? 'green' : 'red' }}-100 px-2 py-1 rounded-full">{{ $transaction->status ? 'Success' : 'Failed' }}</span>
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
        @endforelse

        <div class="py-5">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
