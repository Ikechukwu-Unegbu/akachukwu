@extends('layouts.new-ui')

@section('head')
<title>VASTEL | Nigerian VTU Topup</title>
@endsection 

@section('body')

   
    @include('components.menu-navigation')
    <!-- Hero Section -->
    <section class="flex flex-col gap-5 md:flex-row items-center justify-between px-4 py-12  bg-gray-50 md:px-[3rem]">
        <!-- Text Content -->
        <div class="md:w-1/2 space-y-6">
            <h1 class="text-4xl md:text-5xl font-bold text-vastel_blue">Empower Your Everyday Transactions With Ease</h1>
            <p class="text-lg text-gray-600">Fund Your Account, Send Money, Pay Bills, And More — All In One App.</p>
            <a href="{{route('register')}}"
                class="bg-vastel_blue text-white py-3 px-6 rounded-md hover:bg-blue-800 inline-block text-lg font-semibold">Get
                Started</a>
        </div>

        <!-- Placeholder for Image -->
        <div class="md:w-1/2 flex justify-center md:justify-end mb-8 md:mb-0">
            <img src="{{asset('images/hero-img.svg')}}" alt="Placeholder Image"
                class="w-60 md:w-80 h-auto object-cover">
        </div>
    </section>

    <!-- feature -->

    <section class="py-12 px-4 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Feature Item 1 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="{{asset('images/card.svg')}}" alt="Icon 1">
                </div>
                <p class="text-gray-600">Simplify your bill payments for airtime, data, electricity, and cable TV with our intuitive, all-in-one app</p>
            </div>
            <!-- Feature Item 2 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="{{asset('images/sync.svg')}}" alt="Icon 2">
                </div>
                <p class="text-gray-600">Enjoy absolute peace of mind with our top-notch security, including 2FA and encrypted transactions.</p>
            </div>
            <!-- Feature Item 3 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="{{asset('images/upgrade.svg')}}" alt="Icon 3">
                </div>
                <p class="text-gray-600">Upgrade to a Reseller account, earn by reselling airtime and data, and manage everything.</p>
            </div>
            <!-- Feature Item 4 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md md:col-span-2">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="{{asset('images/widget.svg')}}" alt="Icon 3">
                </div>
                <p class="text-gray-600">Access a wide range of services, from funding your wallet to managing transactions and earning referral bonuses.</p>
            </div>
            <!-- Feature Item 5 -->
            <div class="bg-blue-50 p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 h-12 mb-4 bg-white rounded-full">
                    <!-- Placeholder for Icon -->
                    <img src="{{asset('images/card.svg')}}" alt="Icon 1">
                </div>
                <p class="text-gray-600">Simplify your bill payments for airtime, data, electricity, and cable TV with our intuitive, all-in-one app</p>
            </div>
        </div>
    </section>

    <!--  -->


    
    <div class="bg-gray-50">
        <section class="max-w-7xl mx-auto px-4 py-12">
            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-4xl font-semibold text-vastel_blue">How To Get The Best Vastel App</h2>
                <p class="text-gray-600 mt-4">Follow these four easy steps to get the best of the vastel Web and mobile application</p>
            </div>
            <!-- Steps -->
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- Step 1 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="{{asset('images/step1.svg')}}" alt="Step 1" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-vastel_blue mb-2">Step 1</h3>
                    <p class="text-gray-600">Sign up and create your account on the web or mobile app.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="{{asset('images/step2.svg')}}" alt="Step 2" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-vastel_blue mb-2">Step 2</h3>
                    <p class="text-gray-600">Add funds to your wallet.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="{{asset('images/step3.svg')}}" alt="Step 3" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-vastel_blue mb-2">Step 3</h3>
                    <p class="text-gray-600">Explore and use our services — send money, pay bills, and more.</p>
                </div>
                <!-- Step 4 -->
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <img src="{{asset('images/step4.svg')}}" alt="Step 4" class="mx-auto mb-6">
                    <h3 class="font-semibold text-lg text-vastel_blue mb-2">Step 4</h3>
                    <p class="text-gray-600">Refer friends and start earning or become a merchant.</p>
                </div>
            </div>
            <!-- CTA Button -->
            <div class="text-center mt-12">
                <a href="{{route('register')}}" class="bg-vastel_blue text-white py-3 px-6 rounded-lg shadow hover:bg-blue-800 transition duration-300">
                    Open An Account In Minutes →
                </a>
            </div>
        </section>
    </div>


    <!-- download -->
    <div class="flex items-center justify-center min-h-screen bg-white">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between">
        <!-- Phone Image Placeholder -->
        <div class="flex justify-center w-full md:w-1/2">
            <!-- <div class="bg-gray-200 rounded-lg w-72 h-96 md:w-80 md:h-[500px] flex items-center justify-center"> -->
                <!-- Placeholder for the mobile image -->
                 <img src="{{asset('images/phone.svg')}}" alt="">
                <!-- <span class="text-gray-500">Phone Image Placeholder</span> -->
            <!-- </div> -->
        </div>

        <!-- QR Code and Download Section -->
        <div class="mt-8 md:mt-0 md:w-1/2 flex flex-col items-center md:items-start">
            <div class="flex flex-col items-center md:items-start">
                <img src="{{asset('images/qr.svg')}}" alt="QR Code" class="mb-4">
                <p class="text-center md:text-left text-gray-700 text-lg">
                    Use Your Phone's Camera To Scan And Download The Vastel App<br>
                    Available On IOS And Android
                </p>
            </div>
            <div class="flex mt-4 space-x-4">
                <img src="{{asset('images/applestore.svg')}}" alt="Download on the App Store">
                <img src="{{asset('images/playstore.svg')}}" alt="Get it on Google Play">
            </div>
        </div>
    </div>
</div>
    <!-- download ends -->


    <!-- Testimonials Section -->
{{--<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-blue-800 mb-8">What Our Users Are Saying</h2>
            <div class="flex space-x-4 flex-col md:flex-row md:gap-[2rem] gap-[2rem]">
                <!-- Testimonial Item 1 -->
                <div class="max-w-sm bg-white rounded-lg shadow-md p-6">
                    <img src="https://via.placeholder.com/150" alt="User 1" class="w-full h-48 object-cover rounded-lg mb-4">
                    <blockquote class="text-purple-600 text-2xl mb-4">“</blockquote>
                    <p class="text-gray-700 text-lg">This app makes life so much easier—sending money has never been this quick!</p>
                    <p class="mt-4 font-bold">Brightlyn Star</p>
                </div>
                <!-- Testimonial Item 2 -->
                <div class="max-w-sm bg-white rounded-lg shadow-md p-6">
                    <img src="https://via.placeholder.com/150" alt="User 2" class="w-full h-48 object-cover rounded-lg mb-4">
                    <blockquote class="text-purple-600 text-2xl mb-4">“</blockquote>
                    <p class="text-gray-700 text-lg">This app makes life so much easier—sending money has never been this quick!</p>
                    <p class="mt-4 font-bold">Brightlyn Star</p>
                </div>
            </div>
            <!-- Pagination Controls -->
            <div class="text-gray-500 flex items-center space-x-2">
                <span>01</span>
                <span>/</span>
                <span>07</span>
                <button class="text-gray-700 hover:text-blue-800 focus:outline-none">&larr;</button>
                <button class="text-gray-700 hover:text-blue-800 focus:outline-none">&rarr;</button>
            </div>
        </div>
    </div>
</section>--}}

<!-- Footer Section -->
@include('components.footer')

@endsection