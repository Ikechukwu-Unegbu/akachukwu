@extends('layouts.new-guest')

@section('head')
<link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}"/>

<style>
.dashboard_body{
    display: flex;
    flex-direction: row;
    gap: 2rem;
    margin-top: 3rem;

    align-items: center;  
}
</style>
@endsection

@section('body')
<div class="dashboard_body">
    <div class="sidebar_body">
        @include('components.dasboard_sidebar')
    </div>
    <div class="dashboard_section">

        <!-- card indicators -->
        <div class="d-flex box_container">
            <div class="box wallet bg-basic">
                <div class="top-wallet-box text-light">
                    <div class="fs-3 fw-semibold">Total Wallet</div>
                    <div class="wallet-eye">
                        <div class="fs-3">
                            {{ number_format(auth()->user()->account_balance, 2) }}
                        </div>
                        <button class="eye-btn bg-basic">
                            <i class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="box-img-container">
                    <img class="box-img" src="{{asset('images/wallet_icon.png')}}" alt=""/>
                </div>
            </div>
            <div class="box ref bg-prime text-light ">
                <div class="ref-top ">
                    <img class="box-img" src="{{asset('images/ref_icon.png')}}" alt=""/>
                </div>
                <div class="ref-down">
                    <div class="fs-3">Get 1000 naira for free</div>
                    <div>Refer and get paid for every friend that sign up</div>
                     <div>
                        <button class="btn">Copy Link</button>
                    </div>
                </div>
               
            </div>
            {{-- <div class="box "></div> --}}
        </div>
        <!-- end of card indicators -->    
    </div>

</div>
@endsection 