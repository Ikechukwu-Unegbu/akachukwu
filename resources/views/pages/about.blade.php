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
    

 


{{--<footer class="bg-vastel_blue vastel_bg text-white py-10">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Column 1 -->
        <div>
            <h4 class="font-semibold mb-4">Product</h4>
            <ul>
                <li><a href="#" class="hover:underline">Features</a></li>
                <li><a href="#" class="hover:underline">Pricing</a></li>
            </ul>
        </div>
        <!-- Column 2 -->
        <div>
            <h4 class="font-semibold mb-4">Resources</h4>
            <ul>
                <li><a href="#" class="hover:underline">Blog</a></li>
                <li><a href="{{route('privacy')}}" class="hover:underline">Privacy Policy</a></li>
                <li><a href="{{route('refund')}}" class="hover:underline">Refund Policy</a></li>
            </ul>
        </div>
        <!-- Column 3 -->
        <div>
            <h4 class="font-semibold mb-4">Company</h4>
            <ul>
                <li><a href="#" class="hover:underline">About</a></li>
                <li><a href="{{route('register')}}" class="hover:underline">Register</a></li>
            </ul>
        </div>
        <!-- Column 4 -->
        <div>
            <h4 class="font-semibold mb-4">Subscribe to our newsletter</h4>
            <p class="mb-4 text-sm">For product announcements and exclusive insights</p>
            <div class="flex space-x-2">
                <input type="email" placeholder="Enter text" class="w-full p-2 rounded bg-white text-gray-800 focus:outline-none">
                <button class="bg-purple-500 hover:bg-purple-700 text-white py-2 px-4 rounded">Subscribe</button>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="border-t border-gray-700 mt-8 pt-8 text-center">
        <div class="flex justify-center items-center space-x-4 mb-4">
          
            <!-- Social Icons -->
            <div class="flex space-x-4">
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-linkedin"></i></a>
                <a href="#" class="text-white hover:text-gray-400"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <!-- <p>&copy; 2024 Vastel.io</p> -->
        <p class="font-semibold">&copy; 2024 Vastel</p>
<p class="font-semibold">Made with ❤️ by <a href="https://www.halcyonaegisinc.xyz" class="text-white font-semibold hover:underline" target="_blank">Halcyon Aegis Internet</a></p>

    </div>
</footer>--}}

@include('components.footer')

@endsection