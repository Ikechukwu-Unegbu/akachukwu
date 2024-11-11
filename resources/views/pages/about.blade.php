@extends('layouts.new-ui')


@section('body')

   
    @include('components.menu-navigation')
   
    <section class=" bg-white text-center px-4 py-12  bg-gray-50 md:px-[3rem]">
        <div class=" flex flex-col md:flex-row items-center justify-center gap-5 px-4">
            <div class="">
            <div class="">
                <h2 class="text-3xl text-b
                 font-semibold text-vastel_blue mb-4">About Vastel</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    At Vastel, we believe that managing your everyday digital needs should be simple, fast, and accessible. That’s why we’ve created a platform where you can take care of all your essential services with just a few taps.
                </p>
            </div>
            <div class="flex justify-center">
                <button class="bg-vastel_blue text-white font-bold py-2 px-6 rounded-full">
                    Get Started
                </button>
            </div>
            </div>
            <div class=" flex justify-center items-center">
                <img src="{{asset('images/phone.svg')}}" alt="Phone App" class="">
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="py-16">
        <div class="flex flex-col md:flex-row-reverse justify-center items-center gap-10  mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl text-vastel_blue font-semibold">Who We Are</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    We are a dedicated team of tech enthusiasts, developers, and innovators who are passionate about making life easier for you. Our mission is to simplify the way you access and manage services like airtime, internet subscriptions, cable TV, and educational services, all through a user-friendly app.
                </p>
            </div>
            <div class="flex justify-center">
                <div class="flex">
                    <!-- First Image -->
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <img src="{{asset('images/whyus.png')}}" alt="Team Image" class=" w-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="flex flex-col-reverse  md:flex-row-reverse justify-center items-center gap-10  mx-auto px-4">
            
            <div class="flex justify-center">
                <div class="flex">
                    <!-- First Image -->
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <img src="{{asset('images/vision.png')}}" alt="Team Image" class=" w-full object-cover">
                    </div>
                </div>
            </div>
            <div class="text-center mb-12">
                <h2 class="text-3xl text-vastel_blue font-semibold">Our Vision</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                We aim to be the leading digital services platform in the region, known for our reliability, efficiency, and customer-centric approach. We envision a world where managing digital services is hassle-free, allowing you more time to focus on what truly matters.
                </p>
            </div>
        
        </div>
    </section>


    <section class="py-16">
        <div class="flex flex-col md:flex-row-reverse justify-center items-center gap-10  mx-auto px-4">
            <div class="text-center  mb-12">
              
                    <h3 class="text-2xl font-semibold text-vastel_blue mb-4">Why Choose Us?</h3>
                    <ul class="text-lg flex flex-col justifyc-start items-start text-gray-600 space-y-4">
                        <li><strong>Convenience:</strong> Access all your essential services from one app.</li>
                        <li><strong>Security:</strong> Your transactions are protected with top-notch security measures.</li>
                        <li><strong>Customer Support:</strong> Our dedicated support team is always ready to assist you.</li>
                        <li><strong>Rewards:</strong> Earn points and rewards for every transaction.</li>
                    </ul>
                
            </div>
            <div class="flex justify-center">
                <div class="flex">
                    <!-- First Image -->
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <img src="{{asset('images/why.png')}}" alt="Team Image" class=" w-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>
    

 




@include('components.footer')

@endsection