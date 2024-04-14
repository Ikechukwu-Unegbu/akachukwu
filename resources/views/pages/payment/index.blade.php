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
            <div class="dashboard_section_inner">
                <div>
                    <h3 class="text-warning">Fund Your Wallet</h3>
                </div>
                <form action="{{ route('payment.process') }}" method="POST" class="utility-form">
                    @csrf
                    <div class="airtime-group">
                        <div class="mb-3 form-floating">
                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="">
                            <label for="amount">Enter Amount</label>
                            <span style="font-size: 15px" class="text-danger" id="amount_err"></span>
                            @error('amount')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="mb-3">Choose Payment Gateway</h6>
                        @foreach ($payments as $payment)
                        <div class="mb-3">
                            <input type="radio" class="" name="gateway" id="{{ Str::lower($payment->name) }}" value="{{ Str::lower($payment->name) }}" {{ $loop->first ? 'checked' : '' }}>
                            <label for="{{ Str::lower($payment->name) }}">
                                <img src="{{ asset('images/' . Str::lower($payment->name) . '.png') }}" width="100" alt="">
                            </label>
                        </div>
                        @endforeach
                        {{-- <div class="mb-3">
                            <input type="radio" class="" name="gateway" id="paystack" value="paystack" @checked(true)>
                            <label for="paystack">
                                <img src="{{ asset('images/paystack.png') }}" width="100" alt="">
                            </label>
                        </div>
                        <div class="mb-3">
                            <input type="radio" class="" name="gateway" id="flutterwave" value="flutterwave">
                            <label for="flutterwave">
                                <img src="{{ asset('images/flutterwave.png') }}" width="120" alt="">
                            </label>
                        </div> --}}

                        @error('gateway')<span style="font-size: 15px" class="text-danger">  {{ $message }} </span>@enderror
                    </div>
                    <button type="submit" class="btn bg-basic submit text-light">
                        <span id="loader" style="display: none"><i class="fa fa-circle-notch fa-spin"></i> Please wait...</span>                        
                        <span class="continue">Continue</span>                    
                    </button>
                </form>
            </div>
            <!-- end of card indicators -->    
        </div>
        <!-- end of card indicators -->
    </div>
    </div>
@endsection
@push('styles')
    <style>
    .disabled-cursor {
        cursor: not-allowed !important;
    }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#processPayment').on('submit', function(e) {
                e.preventDefault();
                var amount = $('#amount').val();
                form_action(true);
                $("#amount_err").text('');
                if (amount.length == 0) {
                    $("#amount_err").text('The amount field is required.');
                    form_action(false)
                    return;
                }

                $("#amount_err").text('');

                var form = document.getElementById('processPayment');
                form.action = "{{ route('payment.process') }}";
                form.method = 'POST';
                form.submit();
                form_action(false);
                function form_action(action) {
                    // $("#processPayment input").prop("disabled", action);
                    action ? $('.submit').addClass('disabled-cursor') : $('.submit').removeClass('disabled-cursor');
                    action ? $('#loader').show() : $('#loader').hide();
                    action ? $('.continue').hide() : $('.continue').show();
                }

            });
        });
    </script>
@endpush