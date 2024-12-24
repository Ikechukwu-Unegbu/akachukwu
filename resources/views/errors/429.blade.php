@extends('layouts.new-ui')

@section('head')
    <title>VASTEL | Too Many Requests</title>
@endsection

@section('body')
    @include('components.menu-navigation')

    <div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-[#0018A8] to-blue-700 text-white">
        <div class="text-center">
            <!-- Animated Icon -->
            <div class="mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-40 h-40 mx-auto animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m2 0a2 2 0 100-4H7a2 2 0 100 4m13 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6" />
                </svg>
            </div>

            <!-- Heading -->
            <h1 class="text-6xl font-extrabold">429</h1>
            <p class="mt-4 text-xl font-light">
                You’re sending too many requests. Please slow down and try again later.
            </p>

            <!-- Actions -->
            <div class="mt-8">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-bold rounded-full shadow-lg hover:bg-gray-100 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h11M9 21V3M16.5 7.5l3 3-3 3" />
                    </svg>
                    Go Back Home
                </a>
                <a href="{{ url('/contact') }}"
                    class="ml-4 inline-flex items-center px-6 py-3 bg-transparent border border-white text-white font-bold rounded-full hover:bg-white hover:text-blue-600 transition-all">
                    Contact Support
                </a>
            </div>

            <!-- Animated Background Elements -->
            <!-- <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-10 left-10 w-40 h-40 bg-blue-300 opacity-20 rounded-full animate-pulse"></div>
                <div class="absolute bottom-20 right-20 w-64 h-64 bg-blue-200 opacity-30 rounded-full animate-ping"></div>
                <div class="absolute top-1/3 left-1/4 w-32 h-32 bg-blue-400 opacity-10 rounded-full animate-pulse"></div>
            </div> -->
        </div>
    </div>
@endsection
