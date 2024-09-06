@extends('layouts.new-dashboard')

@section('body')
 <!-- Back Button -->
 <a href="#" class="text-blue-600 flex items-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </a>

    <!-- Referral & Bonus Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <div class="text-xl font-bold">₦ 500</div>
                    <div class="text-gray-500">Total Earned</div>
                </div>
                <div class="text-center">
                    <div class="text-xl font-bold">5</div>
                    <div class="text-gray-500">Total Invited</div>
                </div>
            </div>
            <button class="bg-blue-600 text-white py-2 px-4 rounded-lg">Share Link And Earn</button>
        </div>
        <p class="text-center text-gray-500 mt-4">Share your invitation link with your friends to earn</p>
    </div>

    <!-- Referral History Section -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Referral History</h2>
            <button class="bg-blue-600 text-white py-2 px-4 rounded-lg">Withdraw Bonus</button>
        </div>

        <!-- Referral History List -->
        <div class="space-y-4">
            <!-- Repeat this block for each referral -->
            <div class="bg-gray-100 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-bold">Username</div>
                        <div class="text-gray-500">Phone No: 08123456789</div>
                        <div class="text-gray-500 text-sm">2024-09-23 05:13:33</div>
                    </div>
                    <div class="text-blue-600 font-bold">₦100</div>
                </div>
            </div>
            <!-- End of block -->
        </div>
    </div>
@endsection 