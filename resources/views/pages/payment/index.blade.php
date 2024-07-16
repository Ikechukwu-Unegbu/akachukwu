@extends('layouts.new-guest')

@section('head')
<link rel="stylesheet" href="{{asset('css/dashboard_index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/index.css')}}"/>
<link rel="stylesheet" href="{{asset('css/dashboard_sidebar.css')}}"/>
<link rel="stylesheet" href="{{asset('css/ut/airtime.css')}}"/>
<link rel="stylesheet" href="{{asset('css/ut/network_picker.css')}}"/>
<style>
.utility-form{
    width:100%;

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
            <form action="{{ route('payment.process') }}" method="POST" class="utility-form">
                @csrf
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                        <h3 class="text-warning">Fund Your Wallet</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                        <div class="mb-3 form-floating">
                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="">
                            <label for="amount">Enter Amount</label>
                            <span style="font-size: 15px" class="text-danger" id="amount_err"></span>
                            @error('amount')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-floating">
                        <h6 class="mb-3">Choose Payment Gateway</h6>
                        @foreach ($payments as $payment)
                        <div class="mb-3">
                            <input type="radio" class="" name="gateway" id="{{ Str::lower($payment->name) }}" value="{{ Str::lower($payment->name) }}" {{ $loop->first ? 'checked' : '' }}>
                            <label for="{{ Str::lower($payment->name) }}">
                                <img src="{{ asset('images/' . Str::lower($payment->name) . '.png') }}" width="100" alt="">
                            </label>
                        </div>
                        @endforeach
                        @error('gateway')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-warning submit text-light">
                    <span id="loader" style="display: none"><i class="fa fa-circle-notch fa-spin"></i> Please wait...</span>                        
                    <span class="continue">Continue</span>                    
                </button>

                @if (count($accounts))
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="dashboard-container ml-0">
                            <div class="text-center">
                                <h3>Bank Transfer</h3>
                                <p>Add Money via mobile or internet banking</p>
                            </div>
                            <div class="row">
                                @foreach ($accounts as $__account)
                                    <div class="mb-3 col-6 col-md-6 col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Account Number: {{ $__account->account_number }}</h6>
                                                <h6>Account Name: {{ $__account->account_name }}</h6>
                                                <h6>Bank Name: {{ $__account->bank_name }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </form>

            
        </div>
    </div>
@endsection
