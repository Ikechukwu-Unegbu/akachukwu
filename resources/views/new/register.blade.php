@extends('layouts.new-ui')

@section('body')
<header class="bg-white shadow-sm">
    <div class=" px-[6rem] py-2 flex justify-between items-center">
        <!-- Logo Section -->
        <div class="flex items-center space-x-2">
            <img src="{{asset('images/vastel-logo.svg')}}" alt="Vastel Logo" class="h-8">
            <!-- <span class="text-blue-600 text-lg font-semibold">vastel</span> -->
        </div>

        <!-- Contact Support -->
        <div class="flex items-center space-x-1">
            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm1 2h-2c-3.31 0-6 2.69-6 6v2h14v-2c0-3.31-2.69-6-6-6z"/>
            </svg>
            <a href="#" class="text-blue-600 font-medium">Contact Support</a>
        </div>
    </div>
</header>

<div class="flex min-h-screen  md:px-[6rem] gap-[2rem] bg-gray-100">
    <!-- Left Section -->
    <div class="w-full md:w-[50%] bg-white p-8 shadow-lg">
        <div class="mb-6">
            <a href="#" class="text-blue-600"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <h2 class="text-3xl font-bold mb-6">Get started</h2>
        <p class="text-gray-500 mb-4">Create an account so you can pay and purchase top-ups faster.</p>
        <form>
                <div class="relative mb-6">
                    <input type="text" id="username" name="username" placeholder=" " class="floating-label-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <label for="username" class="absolute left-10 top-3 text-gray-500 transition-all">Username</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                </div>

                <div class="relative mb-6">
                    <input type="text" id="first-name" name="first-name" placeholder=" " class="floating-label-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <label for="first-name" class="absolute left-10 top-3 text-gray-500 transition-all">First Name</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                </div>

                <div class="relative mb-6">
                    <input type="text" id="last-name" name="last-name" placeholder=" " class="floating-label-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <label for="last-name" class="absolute left-10 top-3 text-gray-500 transition-all">Last Name</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                </div>

                <div class="relative mb-6">
                    <input type="email" id="email" name="email" placeholder=" " class="floating-label-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <label for="email" class="absolute left-10 top-3 text-gray-500 transition-all">Email</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                </div>

                <div class="relative mb-6">
                    <input type="tel" id="phone-number" name="phone-number" placeholder=" " class="floating-label-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <label for="phone-number" class="absolute left-10 top-3 text-gray-500 transition-all">Phone Number</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                </div>

                <div class="relative mb-6">
                    <input type="password" id="password" name="password" placeholder=" " class="floating-label-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <label for="password" class="absolute left-10 top-3 text-gray-500 transition-all">Password</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                </div>

                <div class="relative mb-6">
                    <input type="password" id="confirm-password" name="confirm-password" placeholder=" " class="floating-label-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <label for="confirm-password" class="absolute left-10 top-3 text-gray-500 transition-all">Confirm Password</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                </div>

                <div class="relative mb-6">
                    <input type="text" id="referral" name="referral" placeholder=" " class="floating-label-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <label for="referral" class="absolute left-10 top-3 text-gray-500 transition-all">Referral (Optional)</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-gift text-gray-400"></i>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Continue
                </button>
            </form>
        <div class="mt-6 text-center">
            <p>Have an account? <a href="#" class="text-blue-600">Click here to login</a></p>
        </div>
        <div class="mt-6 flex justify-center space-x-4">
            <a href="#"><img src="https://via.placeholder.com/150x50?text=App+Store" alt="App Store" class="w-24"></a>
            <a href="#"><img src="https://via.placeholder.com/150x50?text=Google+Play" alt="Google Play" class="w-24"></a>
        </div>
    </div>
    
    <!-- Right Section -->
    <div class="hidden md:w-[50%] lg:flex flex-1 items-center justify-center bg-white p-12">
        <div class="">
            <img src="{{asset('images/registration.svg')}}" alt="Feature Image" class="mb-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Never miss a payment again with Vastel</h2>
            <p class="text-gray-600 mb-4">Pay your electricity, internet, and other utility bills quickly and easily from the palm of your hand.</p>
            <div class="flex space-x-2">
                <div class="w-4 h-1 bg-blue-600 rounded-full"></div>
                <div class="w-4 h-1 bg-gray-300 rounded-full"></div>
                <div class="w-4 h-1 bg-gray-300 rounded-full"></div>
            </div>
        </div>
    </div>
</div>


@endsection