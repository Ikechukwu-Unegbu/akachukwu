@extends('layouts.new-guest')
@section('body')
    <!-- Back Button -->
    <div class="max-w-7xl mx-auto py-6 px-4">
        <a href="{{ route('settings.index') }}" class="text-vastel_blue text-sm font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <!-- Help & Support Section -->
    <div class="w-[100%] lg:w-[60%] bg-white p-6">
        <h2 class="text-2xl font-semibold text-gray-900 mb-2">Help and Support</h2>
        <p class="text-sm text-gray-600 mb-4">Select your preferred option to contact our customer service</p>

        <!-- Contact Options -->
        <ul class="space-y-4">
            <!-- Email Option -->
            <li class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <a href="mailto:support@vastel.io" class="flex items-center space-x-4">
                    <i class="fas fa-envelope text-vastel_blue text-2xl"></i>
                    <div>
                        <h3 class="text-gray-900 font-semibold">Email</h3>
                        <p class="text-sm text-gray-500">support@vastel.io</p>
                    </div>
                </a>
                <i class="fas fa-chevron-right text-gray-500"></i>
            </li>

            <!-- Phone Option -->
            <li class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <a  href="tel:  {{$settings->phone1}}{{ $settings->phone1 && $settings->phone2 ? ', ' : '' }}{{$settings->phone2}}" class="flex items-center space-x-4">
                    <i class="fas fa-phone-alt text-vastel_blue text-2xl"></i>
                    <div>
                        <h3 class="text-gray-900 font-semibold">Phone</h3>
                        <p class="text-sm text-gray-500">
                            {{$settings->phone1}}{{ $settings->phone1 && $settings->phone2 ? ', ' : '' }}{{$settings->phone2}}
                        </p>

                    </div>
                </a>
                <i class="fas fa-chevron-right text-gray-500"></i>
            </li>


            <!-- Live Chat Option -->
            <li  id="triggerZohoChat"  class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div  class="flex items-center space-x-4">
                    <i class="fas fa-comments text-vastel_blue text-2xl"></i>
                    <div>
                        <h3 class="text-gray-900 font-semibold">Live Chat</h3>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-500"></i>
            </li>
        </ul>
    </div>

  
@endsection