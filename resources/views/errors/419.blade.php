@extends('layouts.new-ui')

@section('head')
    <title>VASTEL | Session Expired</title>
@endsection

@section('body')
    @include('components.menu-navigation')

    <div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-[#0018A8] to-blue-700 text-white">
        <div class="text-center">
            <!-- Animated Icon -->
            <div class="mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-40 h-40 mx-auto animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m0 14v1m8-8h-1M4 12H3m15.364-7.364l-.707.707M6.343 17.657l-.707.707M16.243 17.657l.707.707M6.343 6.343l.707.707" />
                </svg>
            </div>

            <!-- Heading -->
            <h1 class="text-6xl font-extrabold">419</h1>
            <p class="mt-4 text-xl font-light">
                Your session has expired. Please refresh the page or go back.
            </p>

            <!-- Actions -->
            <div class="mt-8">
                <a href="javascript:history.back()"
                    class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-bold rounded-full shadow-lg hover:bg-gray-100 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <a href="{{ url('/') }}"
                    class="ml-4 inline-flex items-center px-6 py-3 bg-transparent border border-white text-white font-bold rounded-full hover:bg-white hover:text-blue-600 transition-all">
                    Go to Homepage
                </a>
            </div>
        </div>
    </div>
@endsection
