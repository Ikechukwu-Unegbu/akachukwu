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

<div class="flex min-h-screen  md:px-[6rem] gap-[2rem]">
    <!-- Left Section -->
    <div class="w-full md:w-[50%] bg-white p-8 shadow-lg">
        <div class="mb-6">
            <a href="#" class="text-blue-600"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <h2 class="text-3xl font-bold mb-6">Get started</h2>
        <p class="text-gray-500 mb-4">Create an account so you can pay and purchase top-ups faster.</p>
        <form>
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="text" name="username" id="username" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="first-name" class="block text-sm font-medium text-gray-700">First Name</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="text" name="first-name" id="first-name" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="text" name="last-name" id="last-name" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="email" name="email" id="email" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="phone-number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="tel" name="phone-number" id="phone-number" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="password" name="password" id="password" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="password" name="confirm-password" id="confirm-password" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="referral" class="block text-sm font-medium text-gray-700">Referral (Optional)</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="text" name="referral" id="referral" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-gift text-gray-400"></i>
                    </div>
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
    <div class="hidden md:w-[50%] lg:flex flex-1 items-center shadow-lg justify-center bg-white p-12">
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