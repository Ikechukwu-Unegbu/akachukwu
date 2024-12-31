@extends('layouts.new-ui')

@section('head')
    <title>VASTEL | Important Message</title>
@endsection

@section('body')
    @include('components.menu-navigation')

    <div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-[#0018A8] to-blue-700 text-white">
        <div class="text-center px-4">
            <!-- Animated Icon -->
            <div class="mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-40 h-40 mx-auto animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v4m0 4v4m8-8h-4M4 12H8m7.071-7.071l-2.828 2.828M6.343 17.657l2.828-2.828M16.243 16.243l2.828-2.828M7.757 7.757l2.828 2.828" />
                </svg>
            </div>

            <!-- Heading -->
            <h1 class="text-5xl sm:text-6xl font-extrabold">Notice</h1>
            <p class="mt-4 text-lg sm:text-xl font-light">
            Your account is under review. Please reach out to support.
            </p>

            <!-- Responsive Actions -->
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="javascript:history.back()"
                    class="inline-flex items-center px-5 py-3 bg-white text-blue-600 font-bold rounded-full shadow-lg hover:bg-gray-100 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <a href="{{ url('/') }}"
                    class="inline-flex items-center px-5 py-3 bg-transparent border border-white text-white font-bold rounded-full hover:bg-white hover:text-blue-600 transition-all">
                    Go to Homepage
                </a>
                <a id="triggerZohoChat"
                    class="inline-flex items-center px-5 py-3 bg-blue-500 text-white font-bold rounded-full hover:bg-blue-600 transition-all">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
@endsection
