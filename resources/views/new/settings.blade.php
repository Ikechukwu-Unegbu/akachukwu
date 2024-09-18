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
    <a href="#" class="flex items-center p-4 bg-white shadow rounded-lg">
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
    <a href="#" class="flex items-center p-4 bg-white shadow rounded-lg">
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
@endsection 