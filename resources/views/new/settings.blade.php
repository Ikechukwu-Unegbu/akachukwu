@extends('layouts.new-guest')

@section('body')
<div class="w-full pl-[1.5rem] max-w-md space-y-4">
    <!-- Settings -->
    <a href="{{route('new.security')}}" class="flex items-center p-4 bg-white shadow rounded-lg">
      <div class="text-vastel_blue">
        <i class="fas fa-cog fa-lg"></i> <!-- Font Awesome settings icon -->
      </div>
      <div class="ml-4">
        <h2 class="text-lg font-semibold text-vastel_blue">Settings</h2>
        <p class="text-sm text-gray-500">Account, notification, security</p>
      </div>
      <div class="ml-auto">
        <i class="fas fa-chevron-right text-gray-400"></i> <!-- Right arrow icon -->
      </div>
    </a>

    <!-- My Referral -->
    <a href="{{route('new.referral')}}" class="flex items-center p-4 bg-white shadow rounded-lg">
      <div class="text-vastel_blue">
        <i class="fas fa-user-friends fa-lg"></i> <!-- Font Awesome referral icon -->
      </div>
      <div class="ml-4">
        <h2 class="text-lg font-semibold text-vastel_blue">My Referral</h2>
        <p class="text-sm text-gray-500">Referral bonuses</p>
      </div>
      <div class="ml-auto">
        <i class="fas fa-chevron-right text-gray-400"></i> <!-- Right arrow icon -->
      </div>
    </a>

    <!-- Upgrade to Reseller -->
    <a class="flex items-center p-4 bg-white shadow rounded-lg" data-modal-target="transferModal" data-modal-toggle="transferModal">
      <div class="text-vastel_blue">
        <i class="fas fa-level-up-alt fa-lg"></i> <!-- Font Awesome upgrade icon -->
      </div>
      <div class="ml-4">
        <h2 class="text-lg font-semibold text-vastel_blue">Upgrade to Reseller</h2>
        <p class="text-sm text-gray-500">Become a vasel merchant</p>
      </div>
      <div class="ml-auto">
        <i class="fas fa-chevron-right text-gray-400"></i> <!-- Right arrow icon -->
      </div>
    </a>

    <!-- KYC -->
    <a href="#" class="flex items-center p-4 bg-white shadow rounded-lg">
      <div class="text-vastel_blue">
        <i class="fas fa-id-card fa-lg"></i> <!-- Font Awesome KYC icon -->
      </div>
      <div class="ml-4">
        <h2 class="text-lg font-semibold text-vastel_blue">KYC</h2>
        <p class="text-sm text-gray-500">Submit details to verify your account</p>
      </div>
      <div class="ml-auto">
        <i class="fas fa-chevron-right text-gray-400"></i> <!-- Right arrow icon -->
      </div>
    </a>

    <!-- Help & Support -->
    <a href="{{route('new.support')}}" class="flex items-center p-4 bg-white shadow rounded-lg">
      <div class="text-vastel_blue">
        <i class="fas fa-question-circle fa-lg"></i> <!-- Font Awesome help icon -->
      </div>
      <div class="ml-4">
        <h2 class="text-lg font-semibold text-vastel_blue">Help & Support</h2>
        <p class="text-sm text-gray-500">Help or contact vasel</p>
      </div>
      <div class="ml-auto">
        <i class="fas fa-chevron-right text-gray-400"></i> <!-- Right arrow icon -->
      </div>
    </a>
  </div>



    <!-- withdrawal modal -->
        <!-- Modal -->
<div id="transferModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full h-full bg-black bg-opacity-30 flex items-center justify-center">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-5 rounded-t">
                <h3 class="text-xl font-medium text-gray-900">
                    Confirm Transfer
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="transferModal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 9.293a1 1 0 011.414 0L10 13.586l4.293-4.293a1 1 0 011.414 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-6 space-y-6 text-center">
                <p>You are about to transfer <strong>₦ 1,000</strong> to your wallet balance.</p>
            </div>

            <!-- Modal footer -->
            <div class="flex justify-between p-6">
                <button data-modal-toggle="transferModal" class="text-vastel_blue bg-white border border-vastel_blue focus:ring-4 focus:outline-none focus:ring-vastel_blue rounded-lg text-sm px-5 py-2.5 hover:text-gray-900 hover:bg-gray-100">
                    Cancel
                </button>
                <button class="text-white bg-vastel_blue focus:ring-4 focus:outline-none focus:ring-blue-600 font-medium rounded-lg text-sm px-5 py-2.5">
                    Continue
                </button>
            </div>
        </div>
    </div>
</div>
    <!-- end of withdrawal modal -->

@endsection 