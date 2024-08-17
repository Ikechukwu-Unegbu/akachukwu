@extends('layouts.new-guest')


@section('seo')

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login now to enjoy excellence with Vastel Nig">
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
    <title>VasTel Nig | Login </title>
@endsection 


@section('head')
<link href="{{asset('css\auth.css')}}"  rel="stylesheet"/>
@endsection  

@section('body')
<div class="auth_class">
    <div class="left_auth">
    
    </div>
    <div class="right_auth">
        <form class="form_auth" action="{{route('login')}}" method="POST" >
            @csrf
            <div class="mb-3 fs-3 fw-semibold ">
                <div class="auth_header">
                    Login and Flex
                </div>
            <div>
            @include("components.error_message")
            <div class="mb-3 form-floating">
                <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}" placeholder="">
                <label for="username">Username or Email</label>
            </div>
             <div class="mb-3 form-floating">
                <input type="password" name="password" class="form-control" id="password" placeholder="">
                <label for="password">Password</label>
            </div>
            <div class=""></div>
            <div class="mb-3 form-floating row">
                <small>Forgot your password? <a href="{{route('password.request')}}">Click here</a></small>
            </div>
             <div class="mb-3 form-floating" style="display:flex; flex-direction:row; align-items:center; jusify-content:center; gap:1rem;">
                {{-- <div class=""> --}}
                    {{-- <input type="checkbox" class="form-check-input" style="" name='remember' id="exampleCheck1"> --}}
                    <small class="text-xs " style="font-size: 15px;" for="exampleCheck1">Dont have account? <a class="text-danger" href="{{route('register')}}">Register Here</a></small>
                {{-- </div> --}}
            </div>
          
            <div>
                <button type="submit" class="btn vastel-bg">Login</button>
            </div>
        </form>
    
    </div>
</div>
@endsection 
