@extends('layouts.new-guest')

@section('head')
<link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}"/>
<link rel="stylesheet" href="{{asset('css/ut/airtime.css')}}"/>
<link rel="stylesheet" href="{{asset('css/ut/network_picker.css')}}"/>
@endsection

@section('body')
    <div class="dashboard_body">
        <div class="sidebar_body">
            @include('components.dasboard_sidebar')
        </div>
        <div class="dashboard_section">

            <!-- card indicators -->
            <div class="">
                <div>
                    <h3 class="text-warning">Fund Your Wallet</h3>
                </div>
                <form id="processPayment" class="utility-form">
                    <div class="airtime-group">
                        <div class="form-floating mb-3">
                            <input type="number" name="amount" class="form-control" id="amount" placeholder="">
                            <label for="amount">Enter Amount</label>
                        </div>
                    </div>
                    <div class="airtime-group">
                        <div class="mb-3">
                            <input type="radio" class="" name="payment" id="paystack" @checked(true)>
                            <label for="paystack">Paystack</label>
                        </div>
                        <div class="mb-3">
                            <input type="radio" class="" name="payment" id="flutterwave">
                            <label for="flutterwave">Flutterwave</label>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-basic text-light">
                        
                        Continue
                    
                    </button>
                </form>
            </div>
            <!-- end of card indicators -->    
        </div>
        <!-- end of card indicators -->
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#processPayment').on('submit', function(e) {
                e.preventDefault()
            });
        });
    </script>
@endpush