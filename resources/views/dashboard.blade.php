@extends('layouts.new-guest')

@section('head')
    {{-- 
<link rel="stylesheet" href="{{asset('css/index.css')}}" />
<link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}" />
<link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}" />

<style>
    .dashboard_body {
        display: flex;
        flex-direction: row;
        gap: 2rem;
        margin-top: 3rem;

        /* align-items: center; */
    }
</style>
@endsection

@section('body')
<div class="dashboard_body">
    <div class="sidebar_body">
        @include('components.dasboard_sidebar')
    </div>

    <div class="dashboard_section">
        <div class="dashboard-container">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="mb-3 col-12 col-lg-4 col-xl-4 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="card-content">
                                <p>Wallet Balance</p>
                                <h3>₦ {{ number_format(auth()->user()->account_balance, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 col-12 col-lg-4 col-xl-4 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="card-content">
                                <p>Referral Bonus</p>
                                <h3>₦ 0.0</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 col-12 col-lg-4 col-xl-4 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-content">
                                <p>My Total Referral</p>
                                <h3>0</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-container">
            <h1>Services</h1>
            <div class="row">
                <div class="mb-3 col-6 col-md-3">
                    <a href="{{ route('airtime.index') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Buy Airtime</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-3">
                    <a href="{{ route('data.index') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Buy Data</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-3">
                    <a href="{{ route('electricity.index') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Electricity</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-3">
                    <a href="{{ route('cable.index') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Cable</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="dashboard-container">
            <h1>Transaction History</h1>
            <div class="row">
                <div class="mb-3 col-6 col-md-4">
                    <a href="{{ route('user.transaction.airtime') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Airtime Transactions</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-4">
                    <a href="{{ route('user.transaction.data') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Data Transactions</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-4">
                    <a href="{{ route('user.transaction.electricity') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Electricity Transactions</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-4">
                    <a href="{{ route('user.transaction.cable') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Cable Transactions</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-4">
                    <a href="{{ route('user.transaction.education') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Education Transactions</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-4">
                    <a href="{{ route('user.transaction.wallet') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Wallet Summary</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- card indicators -->
        <div class="d-flex box_container">

        </div>
        <!-- end of card indicators -->
    </div>

</div>
@endsection --}}
@section('body')
    <!-- Wallet Balance -->
    <div class="px-[2rem] bg-white  shadow-xl p-6 mb-8">
        <p class="text-sm mb-2 text-vastel_blue ml-3 md:ml-0">Wallet Balance</p>
        <div class="flex items-center justify-start md:justify-between flex-col md:flex-row">
            <div class="text-vastel_blue w-[100%] pl-3 md:pl-0  md:w-[50%] flex flex-col justify-end">
                <h2 class="text-3xl font-bold">₦ {{ number_format(auth()->user()->account_balance, 2) }}</h2>
                <p class="text-sm text-blue-600 mt-1">Referral Bonus: ₦0 <i class="fas fa-chevron-right"></i></p>
            </div>
            <div class="flex justify-between gap-[2rem]">
                <button data-modal-target="addMoneyModal" data-modal-toggle="addMoneyModal"
                    class="bg-vastel_blue shadow-lg text-white px-4 py-[0.7rem] rounded-[5px] flex items-center justify-center w-[8.8rem] ">
                    <i class="fas fa-plus mr-2"></i> Add Money
                </button>
                <button
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

    <!-- Recent Transactions -->
    <div class="px-[2rem] bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Recent Transactions</h3>
            <a href="{{ route('transactions') }}" class="text-blue-600 text-sm">See all Transactions</a>
        </div>
        <div class="space-y-4">
            @foreach (auth()->user()->transactionHistories(10) as $transaction)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-mobile-alt bg-blue-100 p-2 rounded-full mr-3"></i>
                        <div>
                            <p class="font-semibold">{{ Str::title($transaction->utility) }}</p>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y. h:ia') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-{{ $transaction->status ? 'green' : 'red' }}-500 font-semibold">
                            ₦{{ number_format($transaction->amount, 2) }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->status ? 'Success' : 'Failed' }}</p>
                    </div>
                </div>
            @endforeach
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
                    <h5 class="text-sm font-medium text-gray-600">Virtual accounts</h5>
                    <p class="mb-4 text-xs text-gray-500">Make a transfer to any of the accounts</p>
                    @foreach (auth()->user()->virtualAccounts()->get() as $key => $account)
                        <!-- Account {{ ++$key }} -->
                        <div class="flex items-center justify-between p-4 mb-3 bg-gray-100 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $account->account_name }}</p>
                                {{-- <p class="text-xs text-gray-500">Account Name</p> --}}
                                <p  class="text-sm font-medium text-gray-800 account-number">{{ $account->account_number }}</p>
                            </div>
                            <button class="text-indigo-600 text-sm font-medium copy-button">Copy</button>
                        </div>
                    @endforeach

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
@endsection
@push('scripts')
    <script>
        // const walletAction = document.getElementById('wallet-action');
        // const wallet = document.getElementById('wallet');
        // let isHidden = true;
        // walletAction.addEventListener('click', () => {
        //     if (isHidden) {
        //         wallet.textContent = '*******';
        //         isHidden = false;
        //     } else {
        //         wallet.textContent = '₦ ' + {{ number_format(auth()->user()->account_balance, 2) }};
        //         isHidden = true;
        //     }
        // });

        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(e) {
                if (e.target.classList.contains('copy-button')) {
                    const accountNumberElement = e.target.previousElementSibling; // Targets the span directly before the button
                    copyToClipboard(accountNumberElement.textContent); // Copies the textContent of the span
                }
            });
        });

        function copyToClipboard(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                const successful = document.execCommand('copy');
                const msg = successful ? 'Copied!' : 'Failed to copy!';
                alert(msg);
            } catch (err) {
                alert('Failed to copy!');
            }
            document.body.removeChild(textarea);
        }

    </script>
@endpush
