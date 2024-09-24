@extends('layouts.new-guest')

@section('body')
<div class="w-full lg:w-[60%] bg-white p-6">
        <!-- Back button -->
        <a href="#" class="inline-flex items-center mb-4 text-blue-600 hover:text-blue-800">
            <i class="fas fa-chevron-left mr-2"></i>
            Back
        </a>

        <h1 class="text-2xl font-bold mb-6">Change Password</h1>

        <form>
            <!-- Old Password -->
            <div class="mb-4 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="old-password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Old Password" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>

            <!-- New Password -->
            <div class="mb-4 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="new-password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="New Password" required>
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye text-gray-400"></i>
                </button>
            </div>

            <!-- Confirm New Password -->
            <div class="mb-6 relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="confirm-new-password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Confirm New Password" required>
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