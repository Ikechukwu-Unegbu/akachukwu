@extends('layouts.new-guest')
@section('body')
    <!-- Wallet Balance -->
    <div class="px-[2rem] bg-white  shadow-xl p-6 mb-8">
       <p class="text-sm mb-2 text-vastel_blue ml-5 md:ml-0">Wallet Balance</p>
        <div class="flex items-center justify-start md:justify-between flex-col md:flex-row">
            <div class="text-vastel_blue w-[100%] pl-5 md:pl-0  md:w-[50%] flex flex-col justify-end">
                <h2 class="text-3xl font-bold">₦ {{ number_format(auth()->user()->account_balance, 2) }}</h2>
                <p class="text-sm text-blue-600 mt-1">Referral Bonus: ₦0 <i class="fas fa-chevron-right"></i></p>
            </div>
            <div class="flex justify-between gap-[2rem]">
                <button data-modal-target="addMoneyModal" data-modal-toggle="addMoneyModal"
                    class="bg-vastel_blue shadow-lg text-white px-4 py-[0.7rem] rounded-[5px] flex items-center justify-center w-[8.8rem] ">
                    <i class="fas fa-plus mr-2"></i> Add Money
                </button>
                <button 
                     data-modal-target="transfer-modal" data-modal-toggle="transfer-modal"
                    class="bg-vastel_blue shadow-lg text-white px-4 py-[0.7rem] rounded-[5px] flex items-center justify-center w-[8.8rem] ">
                    <i class="fas fa-exchange-alt mr-2"></i> Transfer
                </button>
            </div>
        </div>
    </div>

    <!-- Services -->
    <div class="grid grid-cols-3 md:grid-cols-6 gap-4 mb-8">
        <a href="{{ route('airtime.index') }}">
            <div class="flex flex-col  items-center text-vastel_blue">
                <i class=" fas fa-mobile-alt text-3xl text-vastel_blue mb-2"></i>
                <span class="text-sm">Airtime</span>
            </div>
        </a>
        <a href="{{ route('data.index') }}">
            <div class="flex flex-col  items-center text-vastel_blue">
                <i class=" fas fa-wifi text-3xl  text-vastel_blue mb-2"></i>
                <span class="text-sm">Data</span>
            </div>
        </a>
        <a href="{{ route('cable.index') }}">
            <div class="flex flex-col  items-center text-vastel_blue">
                <i class=" fas fa-tv text-3xl text-vastel_blue mb-2"></i>
                <span class="text-sm">TV</span>
            </div>
        </a>
        <a href="{{ route('electricity.index') }}">
            <div class="flex flex-col  items-center text-vastel_blue">
                <i class=" fas fa-bolt text-3xl text-vastel_blue mb-2"></i>
                <span class="text-sm">Electricity</span>
            </div>
        </a>
        <a href="{{ route('education.result.index') }}">
            <div class="flex flex-col  items-center text-vastel_blue">
                <i class=" fas fa-globe text-3xl text-vastel_blue mb-2"></i>
                <span class="text-sm">E-PINS</span>
            </div>
        </a>
        <a href="{{ route('services') }}">
            <div class="flex flex-col  items-center text-vastel_blue">
                <!-- <i class=" fas fa-ellipsis-h text-3xl text-blue-500 mb-2"></i> -->
                <i class=" fa-solid text-3xl text-vastel_blue fa-cubes-stacked"></i>
                <span class="text-sm">All Services</span>
            </div>
        </a>
    </div>

    <!-- money transfer -->
    <livewire:component.money-transfer-component/>
    <!-- end money transfer -->

    <!-- Recent Transactions -->
    <div class="px-[2rem] bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Recent Transactions</h3>
            <a href="{{ route('transactions') }}" class="text-blue-600 text-sm">See all Transactions</a>
        </div>
        <div class="space-y-4">
            @forelse (auth()->user()->checkUserTransactionHistories(10, auth()->id()) as $transaction)
            @php
                $parts = explode(' ', class_basename($transaction));
                
                $lastPart = end($parts);
            @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas {{ $transaction->icon }} bg-blue-100 p-2 rounded-full mr-3"></i>
                        <div>
                        <p class="font-semibold">
                            @if (Str::title($transaction->utility) === 'Transfer')
                                {{-- Perform actions specific to "Transfer" --}}
                                @if($transaction->user_id == Auth::user()->id)
                                <span class="text-red">Transfer</span>
                                @else 
                                <span class="text-green">Recieved</span>
                                @endif 
                            @else
                                {{-- Default behavior --}}
                                {{ Str::title($transaction->utility) }}
                            @endif
                        </p>

                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y. h:ia') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-{{ $transaction->status === 1 ? 'green' : ($transaction->status === 0 ? 'red' : 'yellow') }}-500 font-semibold">
                            ₦{{ number_format($transaction->amount, 2) }}</p>
                        <p class="text-sm text-gray-500">{{ Str::title($transaction->vendor_status) }}</p>
                    </div>
                </div>
                @empty
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <h4 class="text-red-500 font-semibold">No Recent Transactions Found!</h4>
                    </div>
                </div>
            @endforelse
        </div>
    </div>



    <!-- modals -->
    <div id="addMoneyModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 z-50 hidden items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-gray-800 bg-opacity-50">
        <div class="relative w-full max-w-md">
            <!-- Modal content -->
            <div class="bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">
                        Add Money
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="addMoneyModal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6">
                    @if (auth()->user()->virtualAccounts()->count())

                    @php $count = 0 @endphp
                    @forelse (auth()->user()->virtualAccounts()->get() as $key => $account)
                        @if ($loop->first) 
                        <h5 class="text-sm font-medium text-gray-600">Virtual accounts</h5>
                        <p class="mb-4 text-xs text-gray-500">Make a transfer to any of the accounts</p> 
                        @endif
                        <!-- Account {{ ++$count }} -->
                        <div class="flex items-center justify-between p-4 mb-3 bg-gray-100 rounded-lg">
                            <div>
                                <dl class="text-sm font-medium text-gray-800">
                                    <div class="flex justify-left gap-4 py-1">
                                        <dt class="font-bold">Account Name:</dt>
                                        <dd>{{ $account->account_name }}</dd>
                                    </div>
                                    <div class="flex justify-left gap-4 py-1">
                                        <dt class="font-bold">Account Number:</dt>
                                        <dd id="account-number-{{ $count }}" class="account-number">{{ $account->account_number }}</dd>
                                    </div>
                                    <div class="flex justify-left gap-4 py-1">
                                        <dt class="font-bold">Bank Name:</dt>
                                        <dd>{{ $account->bank_name }}</dd>
                                    </div>
                                </dl>

                            </div>
                            <button type="button" class="text-indigo-600 text-sm font-medium copy-button" data-target="account-number-{{ $count }}">Copy</button>
                        </div>
                        @empty
                            <h5 class="text-sm font-medium text-gray-600">Get Started with Virtual Accounts</h5>
                            <p class="mb-4 text-xs text-gray-500">Complete your KYC to access virtual accounts for quick and easy transfers.</p> 
                            <a href="{{ route('settings.kyc') }}" class="text-blue-700">Complete KYC Now</a>
                    @endforelse

                    @else
                    <h5 class="font-medium">Get Started with Virtual Accounts</h5>
                    <p class="mb-4">Complete your KYC to access virtual accounts for quick and easy transfers.</p> 
                    <a href="{{ route('settings.kyc') }}" class="text-blue-700">Complete KYC Now</a>
                    @endif  

                    <div class="flex items-center justify-center py-4">
                        <span class="text-sm text-gray-500">OR</span>
                    </div>
                    <a href="{{ route('payment.index') }}">
                        <div class="flex items-center p-4 bg-gray-100 rounded-lg">
                            <i class="fas fa-credit-card text-indigo-600"></i>
                            <button class="ml-3 text-sm font-medium text-indigo-600">Top-up with Card</button>
                        </div>
                    </a>
                </div>
            </div>
        </div>
       
    </div>

    <livewire:component.global.announcement-modal/>

@endsection
@push('scripts')
    <script>
        let buttons = document.querySelectorAll(".copy-button");

        buttons.forEach(button => {
            button.addEventListener("click", () => {
                let targetId = button.getAttribute('data-target');
                let textToCopy = document.getElementById(targetId);

                if (textToCopy) {
                    const storage = document.createElement('textarea');
                    storage.value = textToCopy.innerText;
                    document.body.appendChild(storage);
                    storage.select();
                    storage.setSelectionRange(0, 99999);

                    try {
                        const successful = document.execCommand('copy');
                        if (successful) {
                            toastr.success('Account No. Copied Successfully!');
                        } else {
                            toastr.warning('Failed to copy!');
                        }
                    } catch (err) {
                        toastr.error('Failed to copy!');
                    } finally {
                        document.body.removeChild(storage);
                    }
                } else {
                    toastr.warning('Target element not found!');
                }
            });
        });
    </script>
@endpush
