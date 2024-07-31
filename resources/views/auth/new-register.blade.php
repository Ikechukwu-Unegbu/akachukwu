@extends('layouts.new-guest')

@section('seo')

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register now to enjoy VTU and internet banking services with speed.">
    <meta name="keywords" content="Vastel, VTU top-up, neo bank, Nigeria, airtime top-up, banking app, financial services, mobile payments, utility payments, neo banking">
    <meta name="author" content="Vastel">

    <meta name="robots" content="index, follow">


    <!-- Open Graph Meta Tags for Facebook -->
    <meta property="og:title" content="Vastel - Nigerian VTU Top-Up and Neo Bank App">
    <meta property="og:description" content="Experience effortless VTU top-ups and modern banking services with Vastel. Your reliable partner for mobile payments, airtime top-ups, and banking needs in Nigeria.">
    <meta property="og:image" content="https://www.vastel.io/images/og-image.jpg">
    <meta property="og:url" content="https://www.vastel.io">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Vastel">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Vastel - Nigerian VTU Top-Up and Neo Bank App">
    <meta name="twitter:description" content="Vastel offers convenient VTU top-ups and modern banking solutions in Nigeria. Enjoy seamless transactions and top-notch financial services with our user-friendly app.">
    <meta name="twitter:image" content="https://www.vastel.io/images/twitter-image.jpg">
    <meta name="twitter:url" content="https://www.vastel.io">
    <meta name="twitter:site" content="@Vastel">
    <meta name="twitter:creator" content="@Vastel">
    <title>VasTel Nig | Register</title>
@endsection 

@section('head')
<link href="{{asset('css\auth.css')}}"  rel="stylesheet"/>
@endsection  

@section('body')
<div class="auth_class">
    <div class="left_auth">
    
    </div>
    <div class="right_auth">
        <form class="form_auth" method="POST" action="{{route('register')}}">
            @csrf
            <div class="mb-3 fs-3 fw-semibold ">
                <div class="auth_header">
                    Sign Up and Enjoy Affordable Utilities
                </div>
            <div>
            @include("components.error_message")
            <div class="mb-3 form-floating">
                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="">
                <label for="name">Full Name</label>
            </div>
             <div class="mb-3 form-floating">
                <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}" placeholder="">
                <label for="username">Username</label>
            </div>
             <div class="mb-3 form-floating">
                <input type="text" name="phone_number" class="form-control" id="phone" value="{{ old('phone_number') }}" placeholder="">
                <label for="phone">Phone</label>
            </div>
             <div class="mb-3 form-floating">
                <input type="text" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="">
                <label for="email">Email</label>
            </div>
             <div class="mb-3 form-floating">
                <input type="password" class="form-control" name="password" id="password" placeholder="">
                <label for="password">Password</label>
                <small style="font-size: 0.75rem; color:red;">Include capital letters and numbers</small>
            </div>
             <div class="mb-3 form-floating">
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="">
                <label for="password_confirmation">Confirm Password</label>
            </div>
            <div class="mb-3 form-floating">
                <div class="form-check">
                    <input type="checkbox" name="terms_and_conditions" class="form-check-input" id="exampleCheck1">
                    <label class="text-xs form-check-label" for="exampleCheck1">I agree to <a class="text-danger" href="#"> terms and conditions</a></label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" style="float:right;" class="float-right btn vastel-bg">Register</button>
            </div>
            <div class="mt-4 mb-4">
                <br/>
                <hr class="mt-2 mb-1"/>
                <br/>
            </div>
             <div class="mb-3 form-floating">
                <div class="form-check">
                    {{-- <input type="checkbox" class="form-check-input" id="exampleCheck1"> --}}
                    <label class="text-xs form-check-label" for="">Do you have account already? <a class="text-warning" href="{{ route('new.login') }}">Login here</a></label>
                </div>
            </div>

        </form>
    
    </div>
</div>
@endsection 
