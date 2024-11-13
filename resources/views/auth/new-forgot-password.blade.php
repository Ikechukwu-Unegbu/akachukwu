@extends('layouts.new-ui')

@section('head')
<link href="{{ asset('css/auth.css') }}" rel="stylesheet" />
@endsection  

@section('body')
<div class="flex flex-col md:flex-row h-screen">
    <div class="hidden md:block md:w-1/2 bg-gray-200 p-6">
        <!-- Left side content for larger screens -->
    </div>
    <div class="w-full md:w-1/2 flex items-center justify-center p-6 md:p-8">
        <form class="w-full max-w-md space-y-6" method="POST" action="{{ route('password.email') }}">
            @csrf 
            <div class="text-xl md:text-2xl font-semibold text-gray-800">
                <div class="mb-4 text-center md:text-left">Enter Your Email For Password Reset Link</div>
            </div>
            @if (session('status'))
                <div class="text-green-700 text-sm bg-green-100 border border-green-300 rounded p-4 mb-4">
                    {{ session('status') }}
                </div>
            @endif 
            @include("components.error_message")

            <div class="mb-3">
                <label for="floatingInput" class="sr-only">Email</label>
                <input type="email" name="email" class="form-input block w-full px-3 py-2 border rounded-md placeholder-gray-400 focus:ring-vastel_blue focus:border-vastel_blue" id="floatingInput" placeholder="Email" required />
            </div>
          
            <div>
                <button type="submit" class="w-full px-4 py-2 bg-vastel_blue text-white font-semibold rounded-md hover:bg-vastel_blue transition duration-150">Email Password Reset Link</button>
            </div>
        </form>
    </div>
</div>
@endsection 
