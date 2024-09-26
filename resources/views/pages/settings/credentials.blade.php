@extends('layouts.new-guest')

@section('body')
<div class="w-full lg:w-[60%] bg-white p-6">
        <!-- Back button -->
        <a href="{{route('dashboard')}}" class="inline-flex items-center mb-4 text-blue-600 hover:text-blue-800">
            <i class="fas fa-chevron-left mr-2"></i>
            Back
        </a>

        <h1 class="text-2xl font-bold mb-6">Change Password</h1>

        <form method="POST" action="{{route('update.password')}}">
            @csrf 

            <div class="max-w-2xl mx-auto mt-6 mb-5">
                <!-- Success Message -->
                @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.354 5.647a.5.5 0 00-.707.707l3.646 3.646-3.646 3.646a.5.5 0 00.707.707L10 10.707l3.646 3.646a.5.5 0 00.707-.707L10.707 10l3.646-3.646a.5.5 0 000-.707z"/>
                    </svg>
                    </span>
                </div>
                @endif

                <!-- Error Message -->
                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">Something went wrong.</span>
                    <ul class="mt-2 ml-4 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.354 5.647a.5.5 0 00-.707.707l3.646 3.646-3.646 3.646a.5.5 0 00.707.707L10 10.707l3.646 3.646a.5.5 0 00.707-.707L10.707 10l3.646-3.646a.5.5 0 000-.707z"/>
                    </svg>
                    </span>
                </div>
                @endif

                <!-- General Info Message -->
                @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Heads up!</strong>
                    <span class="block sm:inline">{{ session('info') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.354 5.647a.5.5 0 00-.707.707l3.646 3.646-3.646 3.646a.5.5 0 00.707.707L10 10.707l3.646 3.646a.5.5 0 00.707-.707L10.707 10l3.646-3.646a.5.5 0 000-.707z"/>
                    </svg>
                    </span>
                </div>
                @endif
            </div>

            <!-- Old Password -->
            <div class="mb-4 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="old-password" name="current_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Old Password" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>

            <!-- New Password -->
            <div class="mb-4 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="new-password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="New Password" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>

            <!-- Confirm New Password -->
            <div class="mb-6 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="confirm-new-password" name="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Confirm New Password" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>

            <!-- Save Changes Button -->
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-[8rem] mb-6">
                Save Changes
            </button>
        </form>

        <!-- Change Security Pin Section -->
        <div class="border-t pt-6">
            <h2 class="text-xl font-semibold mb-2">Change Security Pin</h2>
            <p class="text-gray-600 text-sm mb-4">Send a request to change your security pin</p>
            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-[16rem]">
                Request Security Pin Change
            </button>
        </div>
    </div>



    <!-- account deactivation -->
    <div class="w-full lg:w-[60%] bg-white p-6">
        <!-- Title -->
        <h2 class="text-xl font-bold text-blue-600 mb-4">Delete/Deactivate Account</h2>
        
        <!-- Deactivate Account -->
        <a href="#" class="flex items-center p-4 mb-4 bg-gray-50 rounded-lg shadow-sm">
        <div class="bg-blue-100 p-3 rounded-lg">
            <i class="fas fa-trash-alt text-blue-600 fa-lg"></i> <!-- Trash icon -->
        </div>
        <div class="ml-4">
            <h3 class="text-lg font-semibold text-gray-900">Deactivate account</h3>
            <p class="text-sm text-gray-500">Temporarily deactivate my account</p>
        </div>
        <div class="ml-auto">
            <i class="fas fa-chevron-right text-gray-400"></i> <!-- Right chevron icon -->
        </div>
        </a>

        <!-- Delete Account -->
        <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg shadow-sm">
        <div class="bg-blue-100 p-3 rounded-lg">
            <i class="fas fa-trash-alt text-blue-600 fa-lg"></i> <!-- Trash icon -->
        </div>
        <div class="ml-4">
            <h3 class="text-lg font-semibold text-gray-900">Delete account</h3>
            <p class="text-sm text-gray-500">Permanently delete my account</p>
        </div>
        <div class="ml-auto">
            <i class="fas fa-chevron-right text-gray-400"></i> <!-- Right chevron icon -->
        </div>
        </a>
    </div>

    <!-- end of account deactivation -->
@endsection 