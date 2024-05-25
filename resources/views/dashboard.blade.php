@extends('layouts.new-guest')

@section('head')
<link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}" />
<link rel="stylesheet" href="{{asset('css/index.css')}}" />
<link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}" />

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
                    <a href="{{ route('cable.index') }}">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6>Electricity</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-3">
                    <a href="{{ route('electricity.index') }}">
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


            {{-- <div class="box wallet bg-basic">
                <div class="top-wallet-box text-light">
                    <div class="fs-3 fw-semibold">Total Wallet</div>
                    <div class="wallet-eye">
                        <div class="fs-3" id="wallet">
                            ₦ {{ number_format(auth()->user()->account_balance, 2) }}
                        </div>
                        <button class="eye-btn bg-basic" id="wallet-action">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="box-img-container">
                    <img class="box-img" src="{{asset('images/wallet_icon.png')}}" alt="" />
                </div>
            </div>
            <div class="box ref bg-prime text-light ">
                <div class="ref-top ">
                    <img class="box-img" src="{{asset('images/ref_icon.png')}}" alt="" />
                </div>
                <div class="ref-down">
                    <div class="fs-3">Get 1000 naira for free</div>
                    <div>Refer and get paid for every friend that sign up</div>
                    <div>
                        <button class="btn">Copy Link</button>
                    </div>
                </div>

            </div> --}}

        </div>
        <!-- end of card indicators -->
    </div>

</div>
@endsection

@push('scripts')
<script>
    const walletAction = document.getElementById('wallet-action');
        const wallet = document.getElementById('wallet');
        let isHidden = true;
        walletAction.addEventListener('click', () => {
            if (isHidden) {
            wallet.textContent = '*******';
            isHidden = false;
            } else {
                wallet.textContent = '₦ ' + {{ number_format(auth()->user()->account_balance, 2) }}; // Replace $100 with the actual balance value
                isHidden = true;
            }
        });        
</script>
@endpush