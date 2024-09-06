@extends('layouts.new-ui')

@section('head')
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login now to enjoy excellence with Vastel Nig">
    <meta name="keywords" content="Vastel, VTU top-up, neo bank, Nigeria, airtime top-up, financial services, mobile payments, utility payments">
    <meta name="author" content="Vastel">

    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags for Facebook -->
    <meta property="og:title" content="Vastel - Nigerian VTU Top-Up">
    <meta property="og:description" content="Experience effortless VTU top-ups with Vastel. Your reliable partner for mobile payments, airtime top-ups.">
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

@section('body')
<header class="bg-white shadow-sm">
    <div class="px-4 py-2 h-[4rem] md:px-[6rem] flex justify-between items-center">
        <!-- Logo Section -->
        <div class="flex items-center space-x-2">
            <a href="/"><img src="{{asset('images/vastel-logo.svg')}}" alt="Vastel Logo" class="h-8 w-8 md:h-[5rem] md:w-[5rem]"></a>
        </div>

        <!-- Contact Support -->
        <div class="flex items-center space-x-1">
            <svg class="h-6 w-6 text-vastel_blue" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm1 2h-2c-3.31 0-6 2.69-6 6v2h14v-2c0-3.31-2.69-6-6-6z"/>
            </svg>
            <a href="#" class="text-vastel_blue font-medium text-sm md:text-base">Contact Support</a>
        </div>
    </div>
</header>


<div class="flex min-h-screen  md:px-[6rem] py-[2rem] gap-[2rem] bg-gray-100">
    <!-- Left Section -->
    <div class="w-full md:w-[50%] bg-white p-8 shadow-lg">
        <div class="mb-6">
            <a href="/" class="text-vastel_blue"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <h2 class="text-3xl font-bold mb-6">Welcome back</h2>
        <p class="text-gray-500 mb-4">Login to you account so you can pay and purchase top-ups faster.</p>
        <form method="POST" action="{{route('login')}}">
            @csrf
            @include("components.error_message")
                <div class="relative mb-6">
                    <input type="text" id="username" name="username" placeholder=" " class="floating-label-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <label for="username" class="absolute left-10 top-3 text-gray-500 transition-all">Username or Email</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                </div>

              

           

                <div class="relative mb-6">
    <input type="password" id="password" name="password" placeholder=" " class="floating-label-input block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
    <label for="password" class="absolute left-10 top-3 text-gray-500 transition-all">Password</label>
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i class="fas fa-lock text-gray-400"></i>
    </div>
    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400" onclick="togglePasswordVisibility('password', this)">
        <i class="fas fa-eye"></i>
    </button>
</div>

            <div class="mb-3 form-floating">
                <div class="form-check">
                    <input type="checkbox" name="terms_and_conditions" class="form-check-input" id="exampleCheck1">
                    <label class="text-xs form-check-label" for="exampleCheck1">Remember me?</label>
                </div>
            </div>


                

                <button type="submit" class="w-full bg-vastel_blue text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Continue
                </button>
            </form>
        <div class="mt-6 text-center font-semibold">
            <p>Don't have account? <a href="{{route('register')}}" class="text-vastel_blue font-semibold">Click here to Register</a></p>
        </div>
        <div class="mt-6 flex justify-center space-x-4">
            <a href="#"><img src="{{asset('images/playstore.svg')}}" alt="App Store" class="w-24"></a>
            <a href="#"><img src="{{asset('images/applestore.svg')}}" alt="Google Play" class="w-24"></a>
        </div>
    </div>
    
    <!-- Right Section -->
    <div class="hidden md:w-[50%] lg:flex flex-1 items-center justify-center bg-gray-100 p-12">
        <div class="">
            <img src="{{asset('images/registration.svg')}}" alt="Feature Image" class="mb-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Never miss a payment again with Vastel</h2>
            <p class="text-gray-600 mb-4">Pay your electricity, internet, and other utility bills quickly and easily from the palm of your hand.</p>
            <div class="flex space-x-2">
                <div class="w-4 h-1 bg-vastel_blue rounded-full"></div>
                <div class="w-4 h-1 bg-gray-300 rounded-full"></div>
                <div class="w-4 h-1 bg-gray-300 rounded-full"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection