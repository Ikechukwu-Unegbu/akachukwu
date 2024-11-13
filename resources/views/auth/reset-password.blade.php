@extends('layouts.new-ui')
@section('head')
<style>
/* You can add any custom styles if needed */
</style>
@endsection

@section('body')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <form method="POST" action="{{ route('password.store') }}" class="w-full max-w-md bg-white p-6 rounded-lg shadow-md space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium text-gray-700" />
            <x-text-input 
                id="email" 
                class="form-control w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring-vastel_blue focus:border-vastel_blue" 
                type="email" 
                name="email" 
                :value="old('email', $request->email)" 
                required 
                autofocus 
                autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700" />
            <x-text-input 
                id="password" 
                class="form-control w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring-vastel_blue focus:border-vastel_blue" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password" 
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700" />
            <x-text-input 
                id="password_confirmation" 
                class="form-control w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring-vastel_blue focus:border-vastel_blue" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password" 
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full mt-4 px-4 py-2 bg-vastel_blue text-white font-semibold rounded-md hover:bg-vastel_blue transition duration-150">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</div>
@endsection
