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

    .dashboard-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f4f4f4;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
    }

    .data {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .data-item {
        flex-basis: 30%;
        text-align: center;
        padding: 10px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .data-item h2 {
        margin-top: 0;
        font-size: 24px;
    }

    .data-item p {
        margin: 5px 0;
        font-size: 16px;
    }

    .balance {
        text-align: center;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .balance h2 {
        margin-top: 0;
        font-size: 24px;
    }

    .balance p {
        margin: 5px 0;
        font-size: 16px;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
        cursor: pointer;
    }

    .overlay p {
        color: #fff;
        font-size: 20px;
    }

    .transactions {
        margin-top: 30px;
    }

    .transaction-card {
        background-color: #fff;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .transaction-card h3 {
        margin-top: 0;
        font-size: 18px;
    }

    .transaction-card p {
        margin: 5px 0;
        font-size: 14px;
    }

    .transaction-card .amount {
        color: green;
    }

    .transaction-card.expense .amount {
        color: red;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        height: 100%;
    }

    .card-body {
        padding: 0;
    }

    /* Example styles for card title and content */
    .card-title {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .card-content {
        font-size: 16px;
        text-align: center;
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
                        <div class="card-body">
                            <div class="card-content">
                                <p>Wallet Balance</p>
                                <h3>₦ {{ number_format(auth()->user()->account_balance, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 col-12 col-lg-4 col-xl-4 col-md-4">
                    <div class="card">
                        <div class="card-body">
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
            {{-- <div class="balance">
                <h2>Total Balance</h2>
                <p>$70</p>
            </div> --}}


        </div>

        <div class="dashboard-container">
            <h1>Transaction History</h1>
            <div class="row">
                <div class="mb-3 col-6 col-md-4">
                    <a href="">
                        <div class="card">
                            <div class="card-body">
                                <h6>Airtime Transactions</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-4">
                    <a href="">
                        <div class="card">
                            <div class="card-body">
                                <h6>Data Transactions</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-4">
                    <a href="">
                        <div class="card">
                            <div class="card-body">
                                <h6>Electricity Transactions</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-4">
                    <a href="">
                        <div class="card">
                            <div class="card-body">
                                <h6>Cable Transactions</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3 col-6 col-md-4">
                    <a href="">
                        <div class="card">
                            <div class="card-body">
                                <h6>Wallet Summary</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="mt-4 row">

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